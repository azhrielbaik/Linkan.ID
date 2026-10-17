// Force the page to load at top unless deep-linked by hash
if ("scrollRestoration" in history) {
    history.scrollRestoration = "manual";
}
if (!window.location.hash) {
    window.scrollTo(0, 0);
}
