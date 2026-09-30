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
using System.Text.Json;

namespace DoarMais.Forms
{
    public partial class FrmCadastro : Form
    {
        private readonly UsuarioDAO _usuarioDAO = new();

        private readonly string[] _ufs =
        {
            "AC","AL","AP","AM","BA","CE","DF","ES","GO","MA",
            "MT","MS","MG","PA","PB","PR","PE","PI","RJ","RN",
            "RS","RO","RR","SC","SP","SE","TO"
        };

        public FrmCadastro()
        {
            InitializeComponent();
            cboUf.Items.AddRange(_ufs);

            cboTipoUsuario.Items.Add("comum");
            cboTipoUsuario.Items.Add("superadmin");
            cboTipoUsuario.SelectedIndex = 0;
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
                using var doc = JsonDocument.Parse(json);
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

                string uf = root.GetProperty("uf").GetString();
                cboUf.SelectedItem = uf;
            }
            catch
            {
                MessageBox.Show("Erro ao buscar CEP.", "Erro",
                    MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void btnSalvar_Click(object sender, EventArgs e)
        {
            if (string.IsNullOrWhiteSpace(txtNome.Text) ||
                string.IsNullOrWhiteSpace(mtxtCpf.Text) ||
                string.IsNullOrWhiteSpace(txtEmail.Text) ||
                string.IsNullOrWhiteSpace(txtSenha.Text) ||
                string.IsNullOrWhiteSpace(mtxtCep.Text) ||
                string.IsNullOrWhiteSpace(txtLogradouro.Text) ||
                string.IsNullOrWhiteSpace(mtxtNumero.Text) ||
                string.IsNullOrWhiteSpace(txtBairro.Text) ||
                string.IsNullOrWhiteSpace(txtLocalidade.Text) ||
                cboUf.SelectedIndex == -1)
            {
                MessageBox.Show("Preencha todos os campos obrigatórios.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            var usuario = new Usuario
            {
                Nome = txtNome.Text.Trim(),
                Cpf = mtxtCpf.Text.Trim(),
                Email = txtEmail.Text.Trim(),
                DataNasc = mcDataNasc.SelectionStart,
                Tel = mtxtTel1.Text.Trim(),
                Cep = mtxtCep.Text.Trim(),
                Logradouro = txtLogradouro.Text.Trim(),
                Numero = mtxtNumero.Text.Trim(),
                Complemento = txtComplemento.Text.Trim(),
                Bairro = txtBairro.Text.Trim(),
                Localidade = txtLocalidade.Text.Trim(),
                Uf = cboUf.SelectedItem.ToString(),
                Senha = txtSenha.Text,
                TipoUsuario = cboTipoUsuario.SelectedItem.ToString()!
            };

            _usuarioDAO.Inserir(usuario);

            MessageBox.Show("Usuário cadastrado com sucesso!", "Sucesso",
                MessageBoxButtons.OK, MessageBoxIcon.Information);

            this.Close();
        }

        private void btnCancelar_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}