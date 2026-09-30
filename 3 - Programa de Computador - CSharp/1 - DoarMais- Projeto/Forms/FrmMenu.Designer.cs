namespace DoarMais.Forms
{
    partial class FrmMenu
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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(FrmMenu));
            btnCadastrarDoacao = new Button();
            btnListarDoacoes = new Button();
            btnManutencao = new Button();
            btnCadastrarUsuario = new Button();
            btnAlterarCadastro = new Button();
            btnSair = new Button();
            btnVerDoacao = new Button();
            groupBox1 = new GroupBox();
            groupBox2 = new GroupBox();
            btnVoltarLogin = new Button();
            pictureBox1 = new PictureBox();
            groupBox1.SuspendLayout();
            groupBox2.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)pictureBox1).BeginInit();
            SuspendLayout();
            // 
            // btnCadastrarDoacao
            // 
            btnCadastrarDoacao.Location = new Point(6, 21);
            btnCadastrarDoacao.Name = "btnCadastrarDoacao";
            btnCadastrarDoacao.Size = new Size(159, 23);
            btnCadastrarDoacao.TabIndex = 2;
            btnCadastrarDoacao.Text = "📦 Cadastrar Doação";
            btnCadastrarDoacao.TextAlign = ContentAlignment.MiddleLeft;
            btnCadastrarDoacao.UseVisualStyleBackColor = true;
            btnCadastrarDoacao.Click += btnCadastrarDoacao_Click;
            // 
            // btnListarDoacoes
            // 
            btnListarDoacoes.Location = new Point(6, 79);
            btnListarDoacoes.Name = "btnListarDoacoes";
            btnListarDoacoes.Size = new Size(159, 23);
            btnListarDoacoes.TabIndex = 3;
            btnListarDoacoes.Text = "📋 Listar Doações";
            btnListarDoacoes.TextAlign = ContentAlignment.MiddleLeft;
            btnListarDoacoes.UseVisualStyleBackColor = true;
            btnListarDoacoes.Click += btnListarDoacoes_Click;
            // 
            // btnManutencao
            // 
            btnManutencao.Location = new Point(6, 108);
            btnManutencao.Name = "btnManutencao";
            btnManutencao.Size = new Size(159, 23);
            btnManutencao.TabIndex = 4;
            btnManutencao.Text = "🔧 Gerenciar Doações";
            btnManutencao.TextAlign = ContentAlignment.MiddleLeft;
            btnManutencao.UseVisualStyleBackColor = true;
            btnManutencao.Click += btnManutencao_Click;
            // 
            // btnCadastrarUsuario
            // 
            btnCadastrarUsuario.Location = new Point(6, 22);
            btnCadastrarUsuario.Name = "btnCadastrarUsuario";
            btnCadastrarUsuario.Size = new Size(159, 23);
            btnCadastrarUsuario.TabIndex = 5;
            btnCadastrarUsuario.Text = "👤 Cadastrar Usuário";
            btnCadastrarUsuario.TextAlign = ContentAlignment.MiddleLeft;
            btnCadastrarUsuario.UseVisualStyleBackColor = true;
            btnCadastrarUsuario.Click += btnCadastrarUsuario_Click;
            // 
            // btnAlterarCadastro
            // 
            btnAlterarCadastro.Location = new Point(6, 51);
            btnAlterarCadastro.Name = "btnAlterarCadastro";
            btnAlterarCadastro.Size = new Size(159, 23);
            btnAlterarCadastro.TabIndex = 6;
            btnAlterarCadastro.Text = "✏️ Alterar Cadastro";
            btnAlterarCadastro.TextAlign = ContentAlignment.MiddleLeft;
            btnAlterarCadastro.UseVisualStyleBackColor = true;
            btnAlterarCadastro.Click += btnAlterarCadastro_Click;
            // 
            // btnSair
            // 
            btnSair.Location = new Point(229, 351);
            btnSair.Name = "btnSair";
            btnSair.Size = new Size(114, 23);
            btnSair.TabIndex = 7;
            btnSair.Text = "🚪 Sair!";
            btnSair.UseVisualStyleBackColor = true;
            btnSair.Click += btnSair_Click;
            // 
            // btnVerDoacao
            // 
            btnVerDoacao.Location = new Point(6, 50);
            btnVerDoacao.Name = "btnVerDoacao";
            btnVerDoacao.Size = new Size(159, 23);
            btnVerDoacao.TabIndex = 8;
            btnVerDoacao.Text = "👁️ Ver Doação";
            btnVerDoacao.TextAlign = ContentAlignment.MiddleLeft;
            btnVerDoacao.UseVisualStyleBackColor = true;
            btnVerDoacao.Click += btnVerDoacao_Click;
            // 
            // groupBox1
            // 
            groupBox1.Controls.Add(btnListarDoacoes);
            groupBox1.Controls.Add(btnCadastrarDoacao);
            groupBox1.Controls.Add(btnManutencao);
            groupBox1.Controls.Add(btnVerDoacao);
            groupBox1.Font = new Font("Segoe UI", 8.25F, FontStyle.Regular, GraphicsUnit.Point, 0);
            groupBox1.Location = new Point(45, 178);
            groupBox1.Name = "groupBox1";
            groupBox1.Size = new Size(178, 148);
            groupBox1.TabIndex = 9;
            groupBox1.TabStop = false;
            groupBox1.Text = "Doação:";
            // 
            // groupBox2
            // 
            groupBox2.Controls.Add(btnCadastrarUsuario);
            groupBox2.Controls.Add(btnAlterarCadastro);
            groupBox2.Location = new Point(229, 178);
            groupBox2.Name = "groupBox2";
            groupBox2.Size = new Size(182, 148);
            groupBox2.TabIndex = 10;
            groupBox2.TabStop = false;
            groupBox2.Text = "Usuário:";
            // 
            // btnVoltarLogin
            // 
            btnVoltarLogin.Location = new Point(109, 351);
            btnVoltarLogin.Name = "btnVoltarLogin";
            btnVoltarLogin.Size = new Size(114, 23);
            btnVoltarLogin.TabIndex = 11;
            btnVoltarLogin.Text = "🔙 Voltar ao Login";
            btnVoltarLogin.UseVisualStyleBackColor = true;
            btnVoltarLogin.Click += btnVoltarLogin_Click;
            // 
            // pictureBox1
            // 
            pictureBox1.Image = (Image)resources.GetObject("pictureBox1.Image");
            pictureBox1.Location = new Point(12, 12);
            pictureBox1.Name = "pictureBox1";
            pictureBox1.Size = new Size(444, 124);
            pictureBox1.SizeMode = PictureBoxSizeMode.StretchImage;
            pictureBox1.TabIndex = 12;
            pictureBox1.TabStop = false;
            // 
            // FrmMenu
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(466, 447);
            Controls.Add(pictureBox1);
            Controls.Add(btnVoltarLogin);
            Controls.Add(groupBox2);
            Controls.Add(groupBox1);
            Controls.Add(btnSair);
            FormBorderStyle = FormBorderStyle.FixedToolWindow;
            Name = "FrmMenu";
            StartPosition = FormStartPosition.CenterScreen;
            Text = "Menu";
            groupBox1.ResumeLayout(false);
            groupBox2.ResumeLayout(false);
            ((System.ComponentModel.ISupportInitialize)pictureBox1).EndInit();
            ResumeLayout(false);
        }

        #endregion
        private Button btnCadastrarDoacao;
        private Button btnListarDoacoes;
        private Button btnManutencao;
        private Button btnCadastrarUsuario;
        private Button btnAlterarCadastro;
        private Button btnSair;
        private Button btnVerDoacao;
        private GroupBox groupBox1;
        private GroupBox groupBox2;
        private Button btnVoltarLogin;
        private PictureBox pictureBox1;
    }
}