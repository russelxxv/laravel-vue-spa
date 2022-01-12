export function isLoggedIn() {
    return localStorage.getItem("isLogin");
}

export function logIn() {
    localStorage.setItem("isLogin", true);
}

export function logOut() {
    localStorage.setItem("isLogin", false);
}