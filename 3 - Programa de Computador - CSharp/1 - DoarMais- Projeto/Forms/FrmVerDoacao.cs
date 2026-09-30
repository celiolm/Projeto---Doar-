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

namespace DoarMais.Forms
{
    public partial class FrmVerDoacao : Form
    {
        private readonly DoacaoDAO _doacaoDAO = new();
        private List<string> _fotos = new();
        private int _fotoAtual = 0;

        public FrmVerDoacao()
        {
            InitializeComponent();
        }

        private void btnBuscar_Click(object sender, EventArgs e)
        {
            if (!int.TryParse(txtId.Text.Trim(), out int id))
            {
                MessageBox.Show("Digite um ID válido.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            var doacao = _doacaoDAO.BuscarPorId(id);

            if (doacao == null)
            {
                MessageBox.Show("Doação não encontrada.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            txtNome.Text = doacao.NomeUsuario;
            txtCategoria.Text = doacao.Categoria;
            txtSituacao.Text = doacao.Situacao;
            txtDescricao.Text = doacao.Descricao;
            txtCep.Text = doacao.Cep;
            txtLogradouro.Text = doacao.Logradouro;
            txtNumero.Text = doacao.Numero;
            txtComplemento.Text = doacao.Complemento;
            txtBairro.Text = doacao.Bairro;
            txtLocalidade.Text = doacao.Localidade;
            txtUf.Text = doacao.Uf;

            _fotos = _doacaoDAO.BuscarFotos(id);
            _fotoAtual = 0;
            ExibirFoto();
        }

        private void ExibirFoto()
        {
            if (_fotos.Count == 0)
            {
                picFoto.Image = null;
                lblFoto.Text = "0/0";
                return;
            }

            string caminho = _fotos[_fotoAtual];

            try
            {
                using var http = new HttpClient();
                var bytes = http.GetByteArrayAsync(caminho).Result;
                using var ms = new MemoryStream(bytes);
                picFoto.Image = new Bitmap(ms);
            }
            catch
            {
                picFoto.Image = null;
            }

            lblFoto.Text = $"{_fotoAtual + 1}/{_fotos.Count}";
        }

        private void btnAnterior_Click(object sender, EventArgs e)
        {
            if (_fotoAtual > 0)
            {
                _fotoAtual--;
                ExibirFoto();
            }
        }

        private void btnProximo_Click(object sender, EventArgs e)
        {
            if (_fotoAtual < _fotos.Count - 1)
            {
                _fotoAtual++;
                ExibirFoto();
            }
        }

        private void btnFechar_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}