NAME          := wpcomsh
SHELL         := /bin/bash
UNAME         := $(shell uname -s)
REQUIRED_BINS := zip git rsync composer

## check required bins can be found in $PATH
$(foreach bin,$(REQUIRED_BINS),\
	$(if $(shell command -v $(bin) 2> /dev/null),, $(error `$(bin)` not found in $$PATH)))

## handle version info from git tags
ifeq ($(shell git describe --tags --always > /dev/null 2>&1 ; echo $$?), 0)
	VERSION_STRING := $(shell git describe --tags --always | sed -e 's/^v//')
endif

## set paths from the location of the makefile
MAKEFILE   := $(abspath $(lastword $(MAKEFILE_LIST)))
BUILD_SRC  := $(dir $(MAKEFILE))
BUILD_DST  := $(addsuffix build, $(dir $(MAKEFILE)))
BUILD_FILE := $(NAME).$(VERSION_STRING).zip

## get version from wpcomsh.php for tagging
PLUGIN_VERSION_STRING = $(shell awk '/[^[:graph:]]Version/{print $$NF}' $(BUILD_SRC)/wpcomsh.php)

## git related vars
GIT_BRANCH = $(shell git rev-parse --abbrev-ref HEAD)
GIT_REMOTE_FULL = $(shell git for-each-ref --format='%(upstream:short)' $$(git symbolic-ref -q HEAD))
GIT_REMOTE_NAME = $(firstword $(subst /, , $(GIT_REMOTE_FULL)))
GIT_STATUS = $(shell git status -sb | wc -l | awk '{ if($$1 == 1){ print "clean" } else { print "dirty" } }')

## checking for clean tree and all changes pushed/pulled
git.fetch:
	@git fetch $(GIT_REMOTE_NAME)

check:
ifeq ($(WPCOMSH_DEVMODE), 1)
	@ echo Checks skipped: Make is running in development mode.
else
	@ $(MAKE) checkandblockonfail
endif

checkbeforetag:
	@ $(MAKE) check
	@ $(MAKE) checktagandblockonfail

checkandblockonfail: git.fetch
ifneq ($(strip $(shell git diff --exit-code --quiet $(GIT_REMOTE_FULL)..HEAD 2>/dev/null ; echo $$?)),0)
	$(error local branch not in sync with remote, need to git push/pull)
endif

ifneq ($(GIT_STATUS), clean)
	$(error un-committed changes detected in working tree)
endif

checktagandblockonfail: git.fetch
ifneq ($(GIT_BRANCH), master)
	$(error make tag only supports tagging master)
endif

ifneq ($(strip $(shell awk '/define\([[:space:]]*\047WPCOMSH_VERSION.*\)/{print}' $(BUILD_SRC)/wpcomsh.php | grep -q $(PLUGIN_VERSION_STRING) 2>/dev/null; echo $$?)), 0)
	$(error defined WPCOMSH_VERSION does not match plugin version `$(PLUGIN_VERSION_STRING)`)
endif

ifneq ($(strip $(shell git ls-remote --exit-code $(GIT_REMOTE_NAME) refs/tags/v$(PLUGIN_VERSION_STRING) > /dev/null 2>&1; echo $$?)), 2)
	$(error tag `v$(PLUGIN_VERSION_STRING)` already exists)
endif

ifeq ($(strip $(shell git rev-parse --exit-code v$(PLUGIN_VERSION_STRING) 2>/dev/null ; echo $$?)), 0)
	$(error local tag v$(PLUGIN_VERSION_STRING) exits. Did you forget to push the tag ? 'git push $(GIT_REMOTE_NAME) v$(PLUGIN_VERSION_STRING)' )
endif

$(BUILD_DST)/$(BUILD_FILE): $(BUILD_DST)/$(NAME)
	@ echo "fetching submodules..."
	@ git submodule update --init --recursive &>/dev/null

	@ echo "removing vendor dir..."
	@ rm -rf vendor

	@ echo "running composer install..."
	@ composer install --no-dev --optimize-autoloader &>/dev/null

	@ echo "rsync'ing to build dir..."
	@ rsync \
    --quiet \
    --links \
    --recursive \
    --times \
    --perms \
    --exclude-from=$(BUILD_SRC)build-exclude.txt \
    $(BUILD_SRC) \
    $(BUILD_DST)/$(NAME)/

	@ echo "creating zip file..."
	@ cd $(BUILD_DST) && zip -q -r $(BUILD_FILE) $(NAME)/ -x "._*"

	@ echo "DONE!"

$(BUILD_DST)/$(NAME): $(BUILD_DST)
	@ mkdir -p $(BUILD_DST)/$(NAME)

$(BUILD_DST):
	@ mkdir -p $(BUILD_DST)

## build
build: check $(BUILD_DST)/$(BUILD_FILE)

## tag
PUSH_RELEASE_TAG?=true
tag: checkbeforetag
	$(shell git tag v$(PLUGIN_VERSION_STRING))
	@ echo "tag v$(PLUGIN_VERSION_STRING) added."
	@ echo $(PUSH_TAG)
ifeq ($(PUSH_RELEASE_TAG), true)
	$(shell git push $(GIT_REMOTE_NAME) v$(PLUGIN_VERSION_STRING))
	@ echo "tag pushed to $(GIT_REMOTE_NAME)."
else
	@ echo "run 'git push $(GIT_REMOTE_NAME) v$(PLUGIN_VERSION_STRING)' before creating the release"
endif

## CI & other testing
test-public-access: clean build
	/bin/sh ./bin/ci-init-access-tests.sh

test-private-access: clean build
	/bin/sh ./bin/ci-init-access-tests.sh private

## release
release: export RELEASE_BUCKET := pressable-misc
release: build
	$(if $(shell command -v s3cmd 2> /dev/null),, $(error `s3cmd` not found in $$PATH))
	@ echo "uploading to s3 $(RELEASE_BUCKET)..."
	@ s3cmd --verbose put --acl-public --guess-mime-type \
      $(BUILD_DST)/$(BUILD_FILE) s3://$(RELEASE_BUCKET)
	@ echo "DONE!"

## clean
clean: $(BUILD_DST)
	@ echo "removing $(BUILD_DST)"
	@ rm -rf $(BUILD_DST)

.PHONY: check git.fetch submodules release clean checkbeforetag checktagandblockonfail
