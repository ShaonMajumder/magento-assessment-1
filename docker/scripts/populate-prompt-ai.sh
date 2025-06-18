#!/bin/bash

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROMPT_FILE="${SCRIPT_DIR}/prompt-ai.txt"
OUTPUT_FILE="${SCRIPT_DIR}/prompt-output.md"

# Assume project root is 2 levels above this script: project/docker/ai/
PROJECT_ROOT="$(realpath "$SCRIPT_DIR/../..")"

# Create or clear output file with markdown header
{
  echo "# Project Report"
  echo
  echo "**Project Root:** $PROJECT_ROOT"
  echo
  echo "**Generated on:** $(date)"
  echo
} > "$OUTPUT_FILE"

# # Add directory tree in markdown code block
# {
#   echo "## Project Directory Tree"
#   echo
#   echo '```txt'
#   find "$PROJECT_ROOT" -type d \( -name ".git" -o -name "node_modules" -o -name "vendor" -o -name ".DS_Store" \) -prune -false -o -print | sed "s|$PROJECT_ROOT|.|"
#   echo '```'
#   echo
# } >> "$OUTPUT_FILE"

# Function to determine language for code block based on file extension
get_lang() {
  local file="$1"
  case "$file" in
    *.env) echo "env" ;;
    *.yml|*.yaml) echo "yaml" ;;
    *.json) echo "json" ;;
    Dockerfile) echo "dockerfile" ;;
    *.sh) echo "bash" ;;
    *.conf) echo "nginx" ;;
    *.txt) echo "" ;;  # plain text
    *) echo "" ;;      # default: no language
  esac
}

# Iterate over each file path in prompt-ai.txt
while IFS= read -r relative_path; do
  # Skip empty lines or lines starting with #
  [[ -z "$relative_path" || "$relative_path" == \#* ]] && continue

  ABS_PATH="$(realpath "$SCRIPT_DIR/$relative_path" 2>/dev/null)"
  LANG=$(get_lang "$(basename "$relative_path")")
  SANITIZED_PATH=$(echo "$relative_path" | tr -d '\r\n')

  {
    echo "---"
    echo
    echo "### File: \`$SANITIZED_PATH\`"
    echo
    echo "\`\`\`$LANG"
    if [ -f "$ABS_PATH" ]; then
      # Escape lines starting with # for .env files
      if [[ "$LANG" == "env" ]]; then
        sed 's/^\(#\)/\\\1/' "$ABS_PATH"
      else
        cat "$ABS_PATH"
      fi
    else
      echo "[ERROR] File not found: $ABS_PATH"
    fi
    echo
    echo "\`\`\`"
    echo
  } >> "$OUTPUT_FILE"

done < "$PROMPT_FILE"
