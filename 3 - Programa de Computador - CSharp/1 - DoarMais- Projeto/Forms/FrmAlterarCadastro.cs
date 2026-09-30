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
    public partial class FrmAlterarCadastro : Form
    {
        private readonly UsuarioDAO _usuarioDAO = new();
        private Usuario? _usuarioAtual;

        private readonly string[] _ufs =
        {
            "AC","AL","AP","AM","BA","CE","DF","ES","GO","MA",
            "MT","MS","MG","PA","PB","PR","PE","PI","RJ","RN",
            "RS","RO","RR","SC","SP","SE","TO"
        };

        public FrmAlterarCadastro()
        {
            InitializeComponent();
            cboUf.Items.AddRange(_ufs);

            cboTipoUsuario.Items.Add("comum");
            cboTipoUsuario.Items.Add("superadmin");
        }

        private void btnBuscar_Click(object sender, EventArgs e)
        {
            string cpf = mtxtBuscar.Text.Trim();

            if (string.IsNullOrWhiteSpace(cpf))
            {
                MessageBox.Show("Digite o CPF para buscar.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            _usuarioAtual = _usuarioDAO.BuscarPorCpf(cpf);

            if (_usuarioAtual == null)
            {
                MessageBox.Show("Usuário não encontrado.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            // Preenche os campos
            txtNome.Text = _usuarioAtual.Nome;
            mtxtCpf.Text = _usuarioAtual.Cpf;
            txtEmail.Text = _usuarioAtual.Email;
            mcDataNasc.SelectionStart = _usuarioAtual.DataNasc;
            mtxtTel1.Text = _usuarioAtual.Tel;
            mtxtCep.Text = _usuarioAtual.Cep;
            txtLogradouro.Text = _usuarioAtual.Logradouro;
            mtxtNumero.Text = _usuarioAtual.Numero;
            txtComplemento.Text = _usuarioAtual.Complemento;
            txtBairro.Text = _usuarioAtual.Bairro;
            txtLocalidade.Text = _usuarioAtual.Localidade;
            cboUf.SelectedItem = _usuarioAtual.Uf;
            cboTipoUsuario.SelectedItem = _usuarioAtual.TipoUsuario;
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
                cboUf.SelectedItem = root.GetProperty("uf").GetString();
            }
            catch
            {
                MessageBox.Show("Erro ao buscar CEP.", "Erro",
                    MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void btnSalvar_Click(object sender, EventArgs e)
        {
            if (_usuarioAtual == null) return;

            if (string.IsNullOrWhiteSpace(txtNome.Text) ||
                string.IsNullOrWhiteSpace(txtEmail.Text) ||
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

            _usuarioAtual.Nome = txtNome.Text.Trim();
            _usuarioAtual.Email = txtEmail.Text.Trim();
            _usuarioAtual.DataNasc = mcDataNasc.SelectionStart;
            _usuarioAtual.Tel = mtxtTel1.Text.Trim();
            _usuarioAtual.Cep = mtxtCep.Text.Trim();
            _usuarioAtual.Logradouro = txtLogradouro.Text.Trim();
            _usuarioAtual.Numero = mtxtNumero.Text.Trim();
            _usuarioAtual.Complemento = txtComplemento.Text.Trim();
            _usuarioAtual.Bairro = txtBairro.Text.Trim();
            _usuarioAtual.Localidade = txtLocalidade.Text.Trim();
            _usuarioAtual.Uf = cboUf.SelectedItem.ToString();
            _usuarioAtual.TipoUsuario = cboTipoUsuario.SelectedItem.ToString();

            _usuarioDAO.Atualizar(_usuarioAtual);

            MessageBox.Show("Cadastro atualizado com sucesso!", "Sucesso",
                MessageBoxButtons.OK, MessageBoxIcon.Information);

            this.Close();
        }

        private void btnCancelar_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void cboUf_SelectedIndexChanged(object sender, EventArgs e)
        {

        }
    }
}