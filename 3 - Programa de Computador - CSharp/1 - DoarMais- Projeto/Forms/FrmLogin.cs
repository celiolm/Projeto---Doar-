using DoarMais.Database;

namespace DoarMais.Forms
{
    public partial class FrmLogin : Form
    {
        private readonly UsuarioDAO _usuarioDAO = new();

        public FrmLogin()
        {
            InitializeComponent();
        }

        private void btnEntrar_Click(object sender, EventArgs e)
        {
            string email = txtEmail.Text.Trim();
            string senha = txtSenha.Text;

            if (string.IsNullOrEmpty(email) || string.IsNullOrEmpty(senha))
            {
                MessageBox.Show("Preencha e-mail e senha.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            var usuario = _usuarioDAO.Login(email, senha);

            if (usuario == null)
            {
                MessageBox.Show("E-mail ou senha incorretos.", "Erro",
                    MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            if (usuario.TipoUsuario != "superadmin")
            {
                MessageBox.Show("Acesso permitido apenas para administradores.", "Sem permissão",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            LogAcessoDAO.Registrar(usuario.IdUsuario);

            new FrmMenu().Show();
            this.Hide();
        }

        private void btnSair_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }
    }
}