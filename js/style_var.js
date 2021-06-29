const StyleVar = (() => {
    return {
        getVar: (name) => {
            return getComputedStyle(document.documentElement).getPropertyValue(name);
        }
    }
})();