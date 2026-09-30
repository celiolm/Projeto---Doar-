namespace DoarMais.Forms
{
    partial class FrmCadastro
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
            lblCadastro = new Label();
            lblNome = new Label();
            txtNome = new TextBox();
            lblCpf = new Label();
            mtxtCpf = new MaskedTextBox();
            lblEmail = new Label();
            txtEmail = new TextBox();
            lblDataNasc = new Label();
            mcDataNasc = new MonthCalendar();
            lblTel = new Label();
            lblCep = new Label();
            mtxtTel1 = new MaskedTextBox();
            mtxtCep = new MaskedTextBox();
            btnBuscarCep = new Button();
            lblLogradouro = new Label();
            txtLogradouro = new TextBox();
            lblNumero = new Label();
            mtxtNumero = new MaskedTextBox();
            lblComplemento = new Label();
            txtComplemento = new TextBox();
            lblBairro = new Label();
            txtBairro = new TextBox();
            lblUf = new Label();
            lblSenha = new Label();
            lblLocalidade = new Label();
            txtLocalidade = new TextBox();
            cboUf = new ComboBox();
            txtSenha = new TextBox();
            btnSalvar = new Button();
            btnCancelar = new Button();
            lblTipoUsuario = new Label();
            cboTipoUsuario = new ComboBox();
            SuspendLayout();
            // 
            // lblCadastro
            // 
            lblCadastro.AutoSize = true;
            lblCadastro.Font = new Font("Comic Sans MS", 15.75F, FontStyle.Bold, GraphicsUnit.Point, 0);
            lblCadastro.Location = new Point(12, 9);
            lblCadastro.Name = "lblCadastro";
            lblCadastro.Size = new Size(198, 30);
            lblCadastro.TabIndex = 1;
            lblCadastro.Text = "Cadastrar Usuário";
            // 
            // lblNome
            // 
            lblNome.AutoSize = true;
            lblNome.Location = new Point(12, 52);
            lblNome.Name = "lblNome";
            lblNome.Size = new Size(43, 15);
            lblNome.TabIndex = 2;
            lblNome.Text = "Nome:";
            // 
            // txtNome
            // 
            txtNome.Location = new Point(12, 70);
            txtNome.Name = "txtNome";
            txtNome.Size = new Size(324, 23);
            txtNome.TabIndex = 3;
            // 
            // lblCpf
            // 
            lblCpf.AutoSize = true;
            lblCpf.Location = new Point(12, 96);
            lblCpf.Name = "lblCpf";
            lblCpf.Size = new Size(31, 15);
            lblCpf.TabIndex = 4;
            lblCpf.Text = "CPF:";
            // 
            // mtxtCpf
            // 
            mtxtCpf.Location = new Point(12, 114);
            mtxtCpf.Mask = "000,000,000-00";
            mtxtCpf.Name = "mtxtCpf";
            mtxtCpf.Size = new Size(100, 23);
            mtxtCpf.TabIndex = 5;
            // 
            // lblEmail
            // 
            lblEmail.AutoSize = true;
            lblEmail.Location = new Point(12, 140);
            lblEmail.Name = "lblEmail";
            lblEmail.Size = new Size(39, 15);
            lblEmail.TabIndex = 6;
            lblEmail.Text = "Email:";
            // 
            // txtEmail
            // 
            txtEmail.Location = new Point(12, 158);
            txtEmail.Name = "txtEmail";
            txtEmail.Size = new Size(227, 23);
            txtEmail.TabIndex = 7;
            // 
            // lblDataNasc
            // 
            lblDataNasc.AutoSize = true;
            lblDataNasc.Location = new Point(12, 184);
            lblDataNasc.Name = "lblDataNasc";
            lblDataNasc.Size = new Size(117, 15);
            lblDataNasc.TabIndex = 8;
            lblDataNasc.Text = "Data de Nascimento:";
            // 
            // mcDataNasc
            // 
            mcDataNasc.Location = new Point(12, 208);
            mcDataNasc.Name = "mcDataNasc";
            mcDataNasc.TabIndex = 10;
            // 
            // lblTel
            // 
            lblTel.AutoSize = true;
            lblTel.Location = new Point(12, 379);
            lblTel.Name = "lblTel";
            lblTel.Size = new Size(55, 15);
            lblTel.TabIndex = 11;
            lblTel.Text = "Telefone:";
            // 
            // lblCep
            // 
            lblCep.AutoSize = true;
            lblCep.Location = new Point(12, 423);
            lblCep.Name = "lblCep";
            lblCep.Size = new Size(31, 15);
            lblCep.TabIndex = 12;
            lblCep.Text = "CEP:";
            // 
            // mtxtTel1
            // 
            mtxtTel1.Location = new Point(12, 397);
            mtxtTel1.Mask = "(00) 0,0000-0000";
            mtxtTel1.Name = "mtxtTel1";
            mtxtTel1.Size = new Size(100, 23);
            mtxtTel1.TabIndex = 13;
            // 
            // mtxtCep
            // 
            mtxtCep.Location = new Point(12, 441);
            mtxtCep.Mask = "00000-000";
            mtxtCep.Name = "mtxtCep";
            mtxtCep.Size = new Size(100, 23);
            mtxtCep.TabIndex = 14;
            // 
            // btnBuscarCep
            // 
            btnBuscarCep.Location = new Point(118, 441);
            btnBuscarCep.Name = "btnBuscarCep";
            btnBuscarCep.Size = new Size(75, 23);
            btnBuscarCep.TabIndex = 15;
            btnBuscarCep.Text = "🔍 Buscar";
            btnBuscarCep.UseVisualStyleBackColor = true;
            btnBuscarCep.Click += btnBuscarCep_Click;
            // 
            // lblLogradouro
            // 
            lblLogradouro.AutoSize = true;
            lblLogradouro.Location = new Point(12, 467);
            lblLogradouro.Name = "lblLogradouro";
            lblLogradouro.Size = new Size(84, 15);
            lblLogradouro.TabIndex = 16;
            lblLogradouro.Text = "Rua / Avenida:";
            // 
            // txtLogradouro
            // 
            txtLogradouro.Location = new Point(12, 485);
            txtLogradouro.Name = "txtLogradouro";
            txtLogradouro.Size = new Size(330, 23);
            txtLogradouro.TabIndex = 17;
            // 
            // lblNumero
            // 
            lblNumero.AutoSize = true;
            lblNumero.Location = new Point(348, 467);
            lblNumero.Name = "lblNumero";
            lblNumero.Size = new Size(54, 15);
            lblNumero.TabIndex = 18;
            lblNumero.Text = "Número:";
            // 
            // mtxtNumero
            // 
            mtxtNumero.Location = new Point(348, 485);
            mtxtNumero.Mask = "00000A";
            mtxtNumero.Name = "mtxtNumero";
            mtxtNumero.Size = new Size(54, 23);
            mtxtNumero.TabIndex = 19;
            // 
            // lblComplemento
            // 
            lblComplemento.AutoSize = true;
            lblComplemento.Location = new Point(408, 467);
            lblComplemento.Name = "lblComplemento";
            lblComplemento.Size = new Size(87, 15);
            lblComplemento.TabIndex = 20;
            lblComplemento.Text = "Complemento:";
            // 
            // txtComplemento
            // 
            txtComplemento.Location = new Point(408, 485);
            txtComplemento.Name = "txtComplemento";
            txtComplemento.Size = new Size(100, 23);
            txtComplemento.TabIndex = 21;
            // 
            // lblBairro
            // 
            lblBairro.AutoSize = true;
            lblBairro.Location = new Point(12, 511);
            lblBairro.Name = "lblBairro";
            lblBairro.Size = new Size(41, 15);
            lblBairro.TabIndex = 22;
            lblBairro.Text = "Bairro:";
            // 
            // txtBairro
            // 
            txtBairro.Location = new Point(12, 529);
            txtBairro.Name = "txtBairro";
            txtBairro.Size = new Size(203, 23);
            txtBairro.TabIndex = 23;
            // 
            // lblUf
            // 
            lblUf.AutoSize = true;
            lblUf.Location = new Point(430, 511);
            lblUf.Name = "lblUf";
            lblUf.Size = new Size(45, 15);
            lblUf.TabIndex = 24;
            lblUf.Text = "Estado:";
            // 
            // lblSenha
            // 
            lblSenha.AutoSize = true;
            lblSenha.Location = new Point(12, 555);
            lblSenha.Name = "lblSenha";
            lblSenha.Size = new Size(42, 15);
            lblSenha.TabIndex = 26;
            lblSenha.Text = "Senha:";
            // 
            // lblLocalidade
            // 
            lblLocalidade.AutoSize = true;
            lblLocalidade.Location = new Point(221, 511);
            lblLocalidade.Name = "lblLocalidade";
            lblLocalidade.Size = new Size(47, 15);
            lblLocalidade.TabIndex = 27;
            lblLocalidade.Text = "Cidade:";
            // 
            // txtLocalidade
            // 
            txtLocalidade.Location = new Point(221, 529);
            txtLocalidade.Name = "txtLocalidade";
            txtLocalidade.Size = new Size(203, 23);
            txtLocalidade.TabIndex = 28;
            // 
            // cboUf
            // 
            cboUf.FormattingEnabled = true;
            cboUf.Location = new Point(430, 529);
            cboUf.Name = "cboUf";
            cboUf.Size = new Size(78, 23);
            cboUf.TabIndex = 29;
            // 
            // txtSenha
            // 
            txtSenha.Location = new Point(12, 573);
            txtSenha.Name = "txtSenha";
            txtSenha.Size = new Size(203, 23);
            txtSenha.TabIndex = 30;
            txtSenha.UseSystemPasswordChar = true;
            // 
            // btnSalvar
            // 
            btnSalvar.Location = new Point(232, 619);
            btnSalvar.Name = "btnSalvar";
            btnSalvar.Size = new Size(75, 23);
            btnSalvar.TabIndex = 31;
            btnSalvar.Text = "💾 Salvar";
            btnSalvar.UseVisualStyleBackColor = true;
            btnSalvar.Click += btnSalvar_Click;
            // 
            // btnCancelar
            // 
            btnCancelar.Location = new Point(313, 619);
            btnCancelar.Name = "btnCancelar";
            btnCancelar.Size = new Size(75, 23);
            btnCancelar.TabIndex = 32;
            btnCancelar.Text = "Cancelar";
            btnCancelar.UseVisualStyleBackColor = true;
            btnCancelar.Click += btnCancelar_Click;
            // 
            // lblTipoUsuario
            // 
            lblTipoUsuario.AutoSize = true;
            lblTipoUsuario.Location = new Point(221, 555);
            lblTipoUsuario.Name = "lblTipoUsuario";
            lblTipoUsuario.Size = new Size(93, 15);
            lblTipoUsuario.TabIndex = 33;
            lblTipoUsuario.Text = "Tipo de Usuário:";
            // 
            // cboTipoUsuario
            // 
            cboTipoUsuario.FormattingEnabled = true;
            cboTipoUsuario.Location = new Point(221, 573);
            cboTipoUsuario.Name = "cboTipoUsuario";
            cboTipoUsuario.Size = new Size(149, 23);
            cboTipoUsuario.TabIndex = 34;
            // 
            // FrmCadastro
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(787, 664);
            Controls.Add(cboTipoUsuario);
            Controls.Add(lblTipoUsuario);
            Controls.Add(btnCancelar);
            Controls.Add(btnSalvar);
            Controls.Add(txtSenha);
            Controls.Add(cboUf);
            Controls.Add(txtLocalidade);
            Controls.Add(lblLocalidade);
            Controls.Add(lblSenha);
            Controls.Add(lblUf);
            Controls.Add(txtBairro);
            Controls.Add(lblBairro);
            Controls.Add(txtComplemento);
            Controls.Add(lblComplemento);
            Controls.Add(mtxtNumero);
            Controls.Add(lblNumero);
            Controls.Add(txtLogradouro);
            Controls.Add(lblLogradouro);
            Controls.Add(btnBuscarCep);
            Controls.Add(mtxtCep);
            Controls.Add(mtxtTel1);
            Controls.Add(lblCep);
            Controls.Add(lblTel);
            Controls.Add(mcDataNasc);
            Controls.Add(lblDataNasc);
            Controls.Add(txtEmail);
            Controls.Add(lblEmail);
            Controls.Add(mtxtCpf);
            Controls.Add(lblCpf);
            Controls.Add(txtNome);
            Controls.Add(lblNome);
            Controls.Add(lblCadastro);
            FormBorderStyle = FormBorderStyle.FixedToolWindow;
            Name = "FrmCadastro";
            StartPosition = FormStartPosition.CenterScreen;
            Text = "Cadastrar Usuário";
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private Label lblCadastro;
        private Label lblNome;
        private TextBox txtNome;
        private Label lblCpf;
        private MaskedTextBox mtxtCpf;
        private Label lblEmail;
        private TextBox txtEmail;
        private Label lblDataNasc;
        private MonthCalendar mcDataNasc;
        private Label lblTel;
        private Label lblCep;
        private MaskedTextBox mtxtTel1;
        private MaskedTextBox mtxtCep;
        private Button btnBuscarCep;
        private Label lblLogradouro;
        private TextBox txtLogradouro;
        private Label lblNumero;
        private MaskedTextBox mtxtNumero;
        private Label lblComplemento;
        private TextBox txtComplemento;
        private Label lblBairro;
        private TextBox txtBairro;
        private Label lblUf;
        private Label lblSenha;
        private Label lblLocalidade;
        private TextBox txtLocalidade;
        private ComboBox cboUf;
        private TextBox txtSenha;
        private Button btnSalvar;
        private Button btnCancelar;
        private Label lblTipoUsuario;
        private ComboBox cboTipoUsuario;
    }
}