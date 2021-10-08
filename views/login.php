<script type="text/javascript" src="controllers/controller_Login.js"></script>

<div class="login-container animated fadeInDown">
  <div class="loginbox bg-loginfinal">
    <div class="loginbox-title txt-blanco">INICIO DE SESIÓN</div>
    <div class="loginbox-social">
      <div class="social-title txt-blanco">QmoSistemas</div>
      <div class="social-buttons">
        <img src="assets/img/logoblanco.png" alt="" width="120">
      </div>
    </div>
    <div class="loginbox-or">
      <div class="or-line"></div>
    </div>
    <form id="frm_Login">
      <div class="loginbox-textbox">
        <input type="text" id="txt_UsuarioNombre" class="form-control" placeholder="Usuario" required/>
      </div>
      <div class="loginbox-textbox">
        <input type="password" id="txt_UsuarioContrasena" class="form-control" placeholder="Contraseña" required/>
      </div>
      <div class="loginbox-submit">
        <input type="submit" class="btn btn-default btn-block" value="Conectar">
      </div>
    </form>
  </div>
</div>
