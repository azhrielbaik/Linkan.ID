import re

file_path = 'resources/views/homeadminS/shortlink/create.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

# We need to wrap the turbo:load event listener's global parts so they only run once,
# or better yet, move document event listeners outside of turbo:load.

# Let's see the script block
