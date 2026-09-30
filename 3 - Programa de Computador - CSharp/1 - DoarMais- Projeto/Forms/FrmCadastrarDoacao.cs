using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using DoarMais.Database;
using DoarMais.Models;

namespace DoarMais.Forms
{
    public partial class FrmCadastrarDoacao : Form
    {
        private readonly UsuarioDAO _usuarioDAO = new();
        private readonly DoacaoDAO _doacaoDAO = new();
        private Usuario? _usuarioAtual;

        public FrmCadastrarDoacao()
        {
            InitializeComponent();
            CarregarCategorias();
            CarregarUfs();

        }

        private void CarregarCategorias()
        {
            cboCategoria.Items.AddRange(new string[]
            {
                "Móveis",
                "Eletrônicos",
                "Roupas",
                "Livros",
                "Brinquedos",
                "Outros"
            });
        }

        private readonly string[] _ufs =
{
    "AC","AL","AP","AM","BA","CE","DF","ES","GO","MA",
    "MT","MS","MG","PA","PB","PR","PE","PI","RJ","RN",
    "RS","RO","RR","SC","SP","SE","TO"
};

        private void CarregarUfs()
        {
            cboUf.Items.AddRange(_ufs);
        }

        private async void btnBuscarCep_Click(object sender, EventArgs e)
        {
            string cep = mtxtCep.Text.Trim().Replace("-", "");

            if (cep.Length != 8)
            {
                MessageBox.Show("CEP inválido.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            try
            {
                using var http = new HttpClient();
                var json = await http.GetStringAsync($"https://viacep.com.br/ws/{cep}/json/");
                using var doc = System.Text.Json.JsonDocument.Parse(json);
                var root = doc.RootElement;

                if (root.TryGetProperty("erro", out _))
                {
                    MessageBox.Show("CEP não encontrado.", "Atenção",
                        MessageBoxButtons.OK, MessageBoxIcon.Warning);
                    return;
                }

                txtLogradouro.Text = root.GetProperty("logradouro").GetString();
                txtBairro.Text = root.GetProperty("bairro").GetString();
                txtLocalidade.Text = root.GetProperty("localidade").GetString();
                cboUf.SelectedItem = root.GetProperty("uf").GetString();
            }
            catch
            {
                MessageBox.Show("Erro ao buscar CEP.", "Erro",
                    MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void btnBuscarUsuario_Click(object sender, EventArgs e)
        {
            string cpf = mtxtCpf.Text.Trim();

            if (string.IsNullOrWhiteSpace(cpf))
            {
                MessageBox.Show("Digite o CPF do usuário.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            _usuarioAtual = _usuarioDAO.BuscarPorCpf(cpf);

            if (_usuarioAtual == null)
            {
                MessageBox.Show("Usuário não encontrado.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                lblNomeUsuario.Text = "—";
                return;
            }

            lblNomeUsuario.Text = _usuarioAtual.Nome;
        }

        private void btnSalvar_Click(object sender, EventArgs e)
        {
            if (_usuarioAtual == null)
            {
                MessageBox.Show("Busque um usuário primeiro.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            if (string.IsNullOrWhiteSpace(txtTitulo.Text))
            {
                MessageBox.Show("Digite o título da doação.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            if (cboCategoria.SelectedIndex == -1)
            {
                MessageBox.Show("Selecione uma categoria.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            if (string.IsNullOrWhiteSpace(mtxtCep.Text) ||
                string.IsNullOrWhiteSpace(txtLogradouro.Text) ||
                string.IsNullOrWhiteSpace(mtxtNumero.Text) ||
                string.IsNullOrWhiteSpace(txtBairro.Text) ||
                string.IsNullOrWhiteSpace(txtLocalidade.Text) ||
                cboUf.SelectedIndex == -1)
            {
                MessageBox.Show("Preencha o endereço completo.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            var doacao = new Doacao
            {
                IdUsuario = _usuarioAtual.IdUsuario,
                Titulo = txtTitulo.Text.Trim(),
                Categoria = cboCategoria.SelectedItem.ToString()!,
                Descricao = txtDescricao.Text.Trim(),
                Cep = mtxtCep.Text.Trim(),
                Logradouro = txtLogradouro.Text.Trim(),
                Numero = mtxtNumero.Text.Trim(),
                Complemento = txtComplemento.Text.Trim(),
                Bairro = txtBairro.Text.Trim(),
                Localidade = txtLocalidade.Text.Trim(),
                Uf = cboUf.SelectedItem.ToString()!,
                Situacao = "Pendente"
            };

            int idDoacao = _doacaoDAO.Inserir(doacao);

            var cloudinary = new CloudinaryService();

            for (int i = 0; i < _fotos.Count; i++)
            {
                string url = cloudinary.EnviarFoto(_fotos[i]);
                _doacaoDAO.InserirFoto(idDoacao, url, i + 1);
            }

            MessageBox.Show("Doação cadastrada com sucesso!", "Sucesso",
                MessageBoxButtons.OK, MessageBoxIcon.Information);

            this.Close();
        }

        private List<string> _fotos = new();

        private void btnAdicionarFoto_Click(object sender, EventArgs e)
        {
            using var dialog = new OpenFileDialog();
            dialog.Multiselect = true;
            dialog.Filter = "Imagens|*.jpg;*.jpeg;*.png;*.bmp;*.webp";
            dialog.Title = "Selecione as fotos";

            if (dialog.ShowDialog() == DialogResult.OK)
            {
                foreach (var arquivo in dialog.FileNames)
                {
                    _fotos.Add(arquivo);
                    lstFotos.Items.Add(Path.GetFileName(arquivo));
                }
            }
        }

        private void btnCancelar_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void FrmCadastrarDoacao_Load(object sender, EventArgs e)
        {

        }
    }
}