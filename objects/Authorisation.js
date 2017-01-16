function Authorisation() {
    Authorisation.prototype.checkCredentials = function (user, password) {
        return $.post("./engine.php", {
            action: "signIn",
            user: user,
            passw: password
        });
    }

    Authorisation.prototype.getUsername = function () {
        return $.post("./engine.php", {
            async: false,
            action: "getUsername",
        });
    }

    Authorisation.prototype.getCredentials = function () {
        return $.post("./engine.php", {
            async: false,
            action: "getCredentials",
        });
    }

    Authorisation.prototype.sendCode = function (link, user) {
        return $.post("./engine.php", {
            action: "sendCode",
            link: link,
            user: user
        });
    }
}


