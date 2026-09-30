namespace DoarMais.Forms
{
    partial class FrmListaDoacoes
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            lblTitulo = new Label();
            dgvDoacoes = new DataGridView();
            lblFiltro = new Label();
            cboFiltro = new ComboBox();
            btnFiltrar = new Button();
            btnFechar = new Button();
            ((System.ComponentModel.ISupportInitialize)dgvDoacoes).BeginInit();
            SuspendLayout();
            // 
            // lblTitulo
            // 
            lblTitulo.AutoSize = true;
            lblTitulo.Font = new Font("Comic Sans MS", 15.75F, FontStyle.Bold, GraphicsUnit.Point, 0);
            lblTitulo.Location = new Point(12, 9);
            lblTitulo.Name = "lblTitulo";
            lblTitulo.Size = new Size(187, 30);
            lblTitulo.TabIndex = 2;
            lblTitulo.Text = "Lista de Doações";
            // 
            // dgvDoacoes
            // 
            dgvDoacoes.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill;
            dgvDoacoes.ColumnHeadersHeightSizeMode = DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            dgvDoacoes.Location = new Point(12, 69);
            dgvDoacoes.Name = "dgvDoacoes";
            dgvDoacoes.ReadOnly = true;
            dgvDoacoes.Size = new Size(776, 315);
            dgvDoacoes.TabIndex = 3;
            // 
            // lblFiltro
            // 
            lblFiltro.AutoSize = true;
            lblFiltro.Location = new Point(260, 9);
            lblFiltro.Name = "lblFiltro";
            lblFiltro.Size = new Size(37, 15);
            lblFiltro.TabIndex = 4;
            lblFiltro.Text = "Filtro:";
            // 
            // cboFiltro
            // 
            cboFiltro.FormattingEnabled = true;
            cboFiltro.Location = new Point(260, 27);
            cboFiltro.Name = "cboFiltro";
            cboFiltro.Size = new Size(145, 23);
            cboFiltro.TabIndex = 5;
            // 
            // btnFiltrar
            // 
            btnFiltrar.Location = new Point(411, 27);
            btnFiltrar.Name = "btnFiltrar";
            btnFiltrar.Size = new Size(75, 23);
            btnFiltrar.TabIndex = 6;
            btnFiltrar.Text = "🔍 Filtrar";
            btnFiltrar.UseVisualStyleBackColor = true;
            btnFiltrar.Click += btnFiltrar_Click;
            // 
            // btnFechar
            // 
            btnFechar.Location = new Point(375, 402);
            btnFechar.Name = "btnFechar";
            btnFechar.Size = new Size(75, 23);
            btnFechar.TabIndex = 7;
            btnFechar.Text = "Fechar";
            btnFechar.UseVisualStyleBackColor = true;
            btnFechar.Click += btnFechar_Click;
            // 
            // FrmListaDoacoes
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(800, 450);
            Controls.Add(btnFechar);
            Controls.Add(btnFiltrar);
            Controls.Add(cboFiltro);
            Controls.Add(lblFiltro);
            Controls.Add(dgvDoacoes);
            Controls.Add(lblTitulo);
            FormBorderStyle = FormBorderStyle.FixedToolWindow;
            Name = "FrmListaDoacoes";
            StartPosition = FormStartPosition.CenterScreen;
            Text = "Lista Doações";
            ((System.ComponentModel.ISupportInitialize)dgvDoacoes).EndInit();
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private Label lblTitulo;
        private DataGridView dgvDoacoes;
        private Label lblFiltro;
        private ComboBox cboFiltro;
        private Button btnFiltrar;
        private Button btnFechar;
    }
}