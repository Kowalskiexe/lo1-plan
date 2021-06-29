const Header = (() => {
    let fullHeight = parseInt(StyleVar.getVar('--max-height'));
    let compactHeight = parseInt(StyleVar.getVar('--min-height'));
    let curHeight;
    let headerDiv = document.getElementById('header');
    let isTransforming;

    function setHeight(height) {
        curHeight = height;
        headerDiv.style.setProperty('--header-height', height + 'px');
    }
    setHeight(fullHeight);
    
    function onScroll() {
        if (isTransforming) return;
        
        if (window.scrollY <= 10)
        Header.enlarge();
        else
        Header.downgrade();
    }
    window.addEventListener("scroll", onScroll);

    return {
        enlarge: () => {
            if (curHeight < fullHeight - 0.1) {
                isTransforming = true;
                let deltaH = 1;
                if (curHeight + deltaH > fullHeight) {
                    setHeight(fullHeight);
                    isTransforming = false;
                } else {
                    curHeight += deltaH;
                    setHeight(curHeight);
                    setTimeout(Header.enlarge, 2);
                    return;
                }
            } else
                isTransforming = false;
        },
        downgrade: () => {
            if (curHeight > compactHeight + 0.1) {
                isTransforming = true;
                let deltaH = 1;
                if (curHeight - deltaH < compactHeight) {
                    setHeight(compactHeight);
                    isTransforming = false;
                } else {
                    curHeight -= deltaH;
                    setHeight(curHeight);
                    setTimeout(Header.downgrade, 2);
                }
            } else
                isTransforming = false;
        }
    };
})();
