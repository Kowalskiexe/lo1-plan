const Cookies = (() => {
    return {
        COOKIE_LIFESPAN: (30 * 24 * 60 * 60 * 1000), // a month, default cookie lifespan
        getCookie: (name) => {
            let cookies = document.cookie.split(';');
            for (i = 0; i < cookies.length; i++) {
                cookies[i] = cookies[i].trim();
                if (cookies[i].indexOf(name) == 0)
                    return cookies[i].substring(name.length + 1);
            }
            return false;
        },
        setCookie: (name, value, lifespan = Cookies.COOKIE_LIFESPAN) => {
            document.cookie = `${name}=${value}; expires=${new Date(Date.now() + lifespan).toUTCString()}; path=/`;
        }
    }
})();