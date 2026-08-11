import re
import sys

def scope_css(css_content, scope_class):
    # This is a basic CSS scoper. It won't handle @media queries perfectly if we just prepend.
    # We need to parse @media blocks and scope the rules inside them.
    
    # Let's use a simpler approach: we can use less or sass, or we can just do a regex replace.
    # Since it's standard CSS, let's wrap it in a sass block and compile it?
    # Wait, does the system have sass?
    pass
