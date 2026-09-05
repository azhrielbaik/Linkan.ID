// Force the page to always load at the very top (bypassing browser scroll restoration)
if ("scrollRestoration" in history) {
    history.scrollRestoration = "manual";
}
window.scrollTo(0, 0);
