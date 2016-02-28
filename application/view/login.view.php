<div class="row">
    <form method="post" action="/security/validate">
        <div class="col s8 offset-l2" style="margin-top:25px;">
            <div class="row">
                <div class="input-field col s12 center-align">
                    <h4 class="grey-text text-darken-1">Please sign in</h4>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="email" type="email" class="validate" name="gmail">
                    <label for="email">GMail address</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="password" type="password" class="validate" name="password">
                    <label for="password">Password</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="app_password" type="text" class="validate" placeholder="Keep empty if you are signed up" name="app_password">
                    <label for="app_password">Google app password</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <a href="/faq" class="waves-effect waves-light btn">F.A.Q.</a>
                </div>
                <div class="input-field col s6 right-align">
                    <button type="submit" class="btn waves-effect waves-light" name="submit">Login</button>
                </div>
            </div>
        </div>
    </form>
</div>