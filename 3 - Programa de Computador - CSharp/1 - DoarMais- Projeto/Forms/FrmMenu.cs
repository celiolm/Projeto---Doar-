using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace DoarMais.Forms
{
    public partial class FrmMenu : Form
    {
        public FrmMenu()
        {
            InitializeComponent();
        }

        private void btnCadastrarDoacao_Click(object sender, EventArgs e)
        {
            new FrmCadastrarDoacao().ShowDialog();
        }

        private void btnListarDoacoes_Click(object sender, EventArgs e)
        {
            new FrmListaDoacoes().ShowDialog();
        }

        private void btnManutencao_Click(object sender, EventArgs e)
        {
            new FrmManutencaoDoacao().ShowDialog();
        }

        private void btnCadastrarUsuario_Click(object sender, EventArgs e)
        {
            new FrmCadastro().ShowDialog();
        }

        private void btnAlterarCadastro_Click(object sender, EventArgs e)
        {
            new FrmAlterarCadastro().ShowDialog();
        }

        private void btnSair_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        private void btnVerDoacao_Click(object sender, EventArgs e)
        {
            new FrmVerDoacao().ShowDialog();
        }

        private void btnVoltarLogin_Click(object sender, EventArgs e)
        {
            new FrmLogin().Show();
            this.Hide();
        }
    }
}