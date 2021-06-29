const CookiesNotice = (() => {
    let noticeDiv= document.getElementById('cookies-notice');
    return {
        // animated
        hide: () => {
            let deltaH = 1.5;
            let bottom = 0;
            let deltaT = 5;
            let height = noticeDiv.offsetHeight;
            
            let interval = setInterval(() => {
                bottom -= deltaH;
                noticeDiv.style.bottom = bottom + 'px';
            }, deltaT);
            
            setTimeout(() => { clearInterval(interval); }, (height + 5) / deltaH * deltaT); // stop interval loop
        },
        // not animated
        hideImmediately: () => {
            let height = noticeDiv.offsetHeight;
            noticeDiv.style.bottom = `-${height + 5}px`;
        },
        accept: () => {
            console.log('accept');
            CookiesNotice.hide();
            Cookies.setCookie('cookie_accept', true);
            document.location.reload();
        },
        decline: () => {
            console.log('decline');
            CookiesNotice.hide();
        }
    }
})();

if (Cookies.getCookie('cookie_accept') === 'true') {
    CookiesNotice.hideImmediately();
}