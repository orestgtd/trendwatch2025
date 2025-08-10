# Makefile for building Elm project

ELM = elm
SRC = src/Main.elm
OUT = public/index.html

TAILWIND_INPUT = input.css
TAILWIND_OUTPUT = public/output.css

# Default content for input.css
TAILWIND_DEFAULT = '@tailwind base;\n@tailwind components;\n@tailwind utilities;'

.PHONY: all clean test css elm build init review-init review

# Default build: Elm + Tailwind
all: build

# Create initial input.css if it doesn't exist.
# Also silently creates public folder if it doesn't already exist.
init: | public
	@if [ ! -f input.css ]; then \
		echo "Creating default input.css..."; \
		printf $(TAILWIND_DEFAULT) > input.css; \
	else \
		echo "input.css already exists. Skipping."; \
	fi

public:
	@mkdir -p public

# Compile Elm
elm: | public
	$(ELM) make $(SRC) --output=$(OUT)

# Compile Tailwind CSS
css: | public $(TAILWIND_INPUT)
	npx tailwindcss -i $(TAILWIND_INPUT) -o $(TAILWIND_OUTPUT) --minify

# Combined build target
build: elm css

# Clean generated files
clean:
	rm -f $(OUT) $(TAILWIND_OUTPUT)

# Run Elm tests
test:
	elm-test

# Initialize elm-review in this folder
review-init:
	npx elm-review init

# Run elm-review
review:
	npx elm-review
