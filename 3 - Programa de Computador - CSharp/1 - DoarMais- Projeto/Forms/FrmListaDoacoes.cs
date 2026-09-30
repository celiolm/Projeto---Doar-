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
    public partial class FrmListaDoacoes : Form
    {
        private readonly DoacaoDAO _doacaoDAO = new();

        public FrmListaDoacoes()
        {
            InitializeComponent();
            CarregarFiltros();
            CarregarDoacoes();
        }

        private void CarregarFiltros()
        {
            cboFiltro.Items.Add("Todos");
            cboFiltro.Items.Add("Pendente");
            cboFiltro.Items.Add("Aprovado");
            cboFiltro.Items.Add("Recusado");
            cboFiltro.Items.Add("Histórico");
            cboFiltro.SelectedIndex = 0;
        }

        private void CarregarDoacoes(string filtro = "Todos")
        {
            var lista = _doacaoDAO.ListarTodas();

            if (filtro != "Todos")
                lista = lista.Where(d => d.Situacao == filtro).ToList();

            dgvDoacoes.DataSource = lista.Select(d => new
            {
                ID = d.IdDoacao,
                Usuário = d.NomeUsuario,
                Categoria = d.Categoria,
                Situação = d.Situacao
            }).ToList();
        }

        private void btnFiltrar_Click(object sender, EventArgs e)
        {
            string filtro = cboFiltro.SelectedItem.ToString()!;
            CarregarDoacoes(filtro);
        }

        private void btnFechar_Click(object sender, EventArgs e)
        {
            this.Close();
        }

    }
}