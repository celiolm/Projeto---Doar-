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
    public partial class FrmManutencaoDoacao : Form
    {
        private readonly DoacaoDAO _doacaoDAO = new();

        public FrmManutencaoDoacao()
        {
            InitializeComponent();
            CarregarSituacoes();
            CarregarDoacoes();
        }

        private void CarregarSituacoes()
        {
            cboSituacao.Items.Add("Pendente");
            cboSituacao.Items.Add("Aprovado");
            cboSituacao.Items.Add("Recusado");
            cboSituacao.Items.Add("Histórico");
            cboSituacao.SelectedIndex = 0;
        }

        private void CarregarDoacoes()
        {
            var lista = _doacaoDAO.ListarTodas();

            dgvDoacoes.DataSource = lista.Select(d => new
            {
                ID = d.IdDoacao,
                Usuário = d.NomeUsuario,
                Categoria = d.Categoria,
                Situação = d.Situacao
            }).ToList();
        }

        private int? ObterIdSelecionado()
        {
            if (dgvDoacoes.SelectedRows.Count == 0)
            {
                MessageBox.Show("Selecione uma doação.", "Atenção",
                    MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return null;
            }

            return Convert.ToInt32(dgvDoacoes.SelectedRows[0].Cells["ID"].Value);
        }

        private void btnAtualizar_Click(object sender, EventArgs e)
        {
            int? id = ObterIdSelecionado();
            if (id == null) return;

            string situacao = cboSituacao.SelectedItem.ToString()!;
            _doacaoDAO.AtualizarSituacao(id.Value, situacao);

            MessageBox.Show("Situação atualizada com sucesso!", "Sucesso",
                MessageBoxButtons.OK, MessageBoxIcon.Information);

            CarregarDoacoes();
        }

        private void btnDeletar_Click(object sender, EventArgs e)
        {
            int? id = ObterIdSelecionado();
            if (id == null) return;

            var confirmar = MessageBox.Show("Deseja deletar esta doação?", "Confirmar",
                MessageBoxButtons.YesNo, MessageBoxIcon.Question);

            if (confirmar == DialogResult.Yes)
            {
                _doacaoDAO.Deletar(id.Value);

                MessageBox.Show("Doação deletada com sucesso!", "Sucesso",
                    MessageBoxButtons.OK, MessageBoxIcon.Information);

                CarregarDoacoes();
            }
        }

        private void btnFechar_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}