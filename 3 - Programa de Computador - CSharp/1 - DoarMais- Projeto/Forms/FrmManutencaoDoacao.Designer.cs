namespace DoarMais.Forms
{
    partial class FrmManutencaoDoacao
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
            lblSituacao = new Label();
            cboSituacao = new ComboBox();
            btnAtualizar = new Button();
            btnDeletar = new Button();
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
            lblTitulo.Size = new Size(247, 30);
            lblTitulo.TabIndex = 3;
            lblTitulo.Text = "Manutenção da Doação";
            // 
            // dgvDoacoes
            // 
            dgvDoacoes.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill;
            dgvDoacoes.ColumnHeadersHeightSizeMode = DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            dgvDoacoes.Location = new Point(12, 82);
            dgvDoacoes.Name = "dgvDoacoes";
            dgvDoacoes.ReadOnly = true;
            dgvDoacoes.Size = new Size(776, 325);
            dgvDoacoes.TabIndex = 4;
            // 
            // lblSituacao
            // 
            lblSituacao.AutoSize = true;
            lblSituacao.Location = new Point(295, 9);
            lblSituacao.Name = "lblSituacao";
            lblSituacao.Size = new Size(55, 15);
            lblSituacao.TabIndex = 5;
            lblSituacao.Text = "Situação:";
            // 
            // cboSituacao
            // 
            cboSituacao.FormattingEnabled = true;
            cboSituacao.Location = new Point(295, 27);
            cboSituacao.Name = "cboSituacao";
            cboSituacao.Size = new Size(121, 23);
            cboSituacao.TabIndex = 6;
            // 
            // btnAtualizar
            // 
            btnAtualizar.Location = new Point(422, 26);
            btnAtualizar.Name = "btnAtualizar";
            btnAtualizar.Size = new Size(125, 23);
            btnAtualizar.TabIndex = 7;
            btnAtualizar.Text = "✅ Atualizar Situação";
            btnAtualizar.UseVisualStyleBackColor = true;
            btnAtualizar.Click += btnAtualizar_Click;
            // 
            // btnDeletar
            // 
            btnDeletar.Location = new Point(295, 429);
            btnDeletar.Name = "btnDeletar";
            btnDeletar.Size = new Size(75, 23);
            btnDeletar.TabIndex = 8;
            btnDeletar.Text = "🗑️ Deletar";
            btnDeletar.UseVisualStyleBackColor = true;
            btnDeletar.Click += btnDeletar_Click;
            // 
            // btnFechar
            // 
            btnFechar.Location = new Point(376, 429);
            btnFechar.Name = "btnFechar";
            btnFechar.Size = new Size(75, 23);
            btnFechar.TabIndex = 9;
            btnFechar.Text = "Fechar";
            btnFechar.UseVisualStyleBackColor = true;
            btnFechar.Click += btnFechar_Click;
            // 
            // FrmManutencaoDoacao
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(800, 475);
            Controls.Add(btnFechar);
            Controls.Add(btnDeletar);
            Controls.Add(btnAtualizar);
            Controls.Add(cboSituacao);
            Controls.Add(lblSituacao);
            Controls.Add(dgvDoacoes);
            Controls.Add(lblTitulo);
            FormBorderStyle = FormBorderStyle.FixedToolWindow;
            Name = "FrmManutencaoDoacao";
            StartPosition = FormStartPosition.CenterScreen;
            Text = "Gerenciar Doações";
            ((System.ComponentModel.ISupportInitialize)dgvDoacoes).EndInit();
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private Label lblTitulo;
        private DataGridView dgvDoacoes;
        private Label lblSituacao;
        private ComboBox cboSituacao;
        private Button btnAtualizar;
        private Button btnDeletar;
        private Button btnFechar;
    }
}