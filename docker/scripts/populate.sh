#!/bin/bash

# Script to loop through all files in Strativ_ProductTags module and output to Markdown
# Run inside magento-app Docker container: docker exec -it magento-app bash -c "bash /path/to/generate_module_files.sh"

# Define module directory
MODULE_DIR="src/app/code/Strativ/ProductTags"
OUTPUT_FILE="module_files.md"

# Check if module directory exists
if [ ! -d "$MODULE_DIR" ]; then
    echo "Error: Directory $MODULE_DIR does not exist."
    exit 1
fi

# Initialize Markdown file
echo "# Strativ_ProductTags Module Files" > "$OUTPUT_FILE"
echo "Generated on $(date)" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"

# Loop through all files recursively
find "$MODULE_DIR" -type f | while read -r file; do
    # Get relative path
    rel_path="${file#$MODULE_DIR/}"
    
    # Write file path as header
    echo "## $rel_path" >> "$OUTPUT_FILE"
    echo "" >> "$OUTPUT_FILE"
    
    # Write file content in code block
    echo "\`\`\`${rel_path##*.}" >> "$OUTPUT_FILE"
    cat "$file" >> "$OUTPUT_FILE"
    echo "\`\`\`" >> "$OUTPUT_FILE"
    echo "" >> "$OUTPUT_FILE"
done

echo "Markdown file generated at $OUTPUT_FILE"