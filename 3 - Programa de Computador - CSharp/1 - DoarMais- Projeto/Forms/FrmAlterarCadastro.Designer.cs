namespace DoarMais.Forms
{
    partial class FrmAlterarCadastro
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
            lblAlterarCadastro = new Label();
            mtxtBuscar = new MaskedTextBox();
            btnBuscar = new Button();
            cboUf = new ComboBox();
            txtLocalidade = new TextBox();
            lblLocalidade = new Label();
            lblUf = new Label();
            txtBairro = new TextBox();
            lblBairro = new Label();
            txtComplemento = new TextBox();
            lblComplemento = new Label();
            mtxtNumero = new MaskedTextBox();
            lblNumero = new Label();
            txtLogradouro = new TextBox();
            lblLogradouro = new Label();
            btnBuscarCep = new Button();
            mtxtCep = new MaskedTextBox();
            mtxtTel1 = new MaskedTextBox();
            lblCep = new Label();
            lblTel = new Label();
            mcDataNasc = new MonthCalendar();
            lblDataNasc = new Label();
            txtEmail = new TextBox();
            lblEmail = new Label();
            mtxtCpf = new MaskedTextBox();
            lblCpf = new Label();
            txtNome = new TextBox();
            lblNome = new Label();
            btnSalvar = new Button();
            btnCancelar = new Button();
            cboTipoUsuario = new ComboBox();
            lblTipoUsuario = new Label();
            SuspendLayout();
            // 
            // lblAlterarCadastro
            // 
            lblAlterarCadastro.AutoSize = true;
            lblAlterarCadastro.Font = new Font("Comic Sans MS", 15.75F, FontStyle.Bold, GraphicsUnit.Point, 0);
            lblAlterarCadastro.Location = new Point(12, 9);
            lblAlterarCadastro.Name = "lblAlterarCadastro";
            lblAlterarCadastro.Size = new Size(187, 30);
            lblAlterarCadastro.TabIndex = 2;
            lblAlterarCadastro.Text = "Alterar Cadastro";
            // 
            // mtxtBuscar
            // 
            mtxtBuscar.Location = new Point(36, 64);
            mtxtBuscar.Mask = "000,000,000-00";
            mtxtBuscar.Name = "mtxtBuscar";
            mtxtBuscar.Size = new Size(112, 23);
            mtxtBuscar.TabIndex = 3;
            // 
            // btnBuscar
            // 
            btnBuscar.Location = new Point(154, 64);
            btnBuscar.Name = "btnBuscar";
            btnBuscar.Size = new Size(75, 23);
            btnBuscar.TabIndex = 4;
            btnBuscar.Text = "🔍 Buscar";
            btnBuscar.UseVisualStyleBackColor = true;
            btnBuscar.Click += btnBuscar_Click;
            // 
            // cboUf
            // 
            cboUf.FormattingEnabled = true;
            cboUf.Location = new Point(454, 590);
            cboUf.Name = "cboUf";
            cboUf.Size = new Size(78, 23);
            cboUf.TabIndex = 56;
            cboUf.SelectedIndexChanged += cboUf_SelectedIndexChanged;
            // 
            // txtLocalidade
            // 
            txtLocalidade.Location = new Point(245, 590);
            txtLocalidade.Name = "txtLocalidade";
            txtLocalidade.Size = new Size(203, 23);
            txtLocalidade.TabIndex = 55;
            // 
            // lblLocalidade
            // 
            lblLocalidade.AutoSize = true;
            lblLocalidade.Location = new Point(245, 572);
            lblLocalidade.Name = "lblLocalidade";
            lblLocalidade.Size = new Size(47, 15);
            lblLocalidade.TabIndex = 54;
            lblLocalidade.Text = "Cidade:";
            // 
            // lblUf
            // 
            lblUf.AutoSize = true;
            lblUf.Location = new Point(454, 572);
            lblUf.Name = "lblUf";
            lblUf.Size = new Size(45, 15);
            lblUf.TabIndex = 52;
            lblUf.Text = "Estado:";
            // 
            // txtBairro
            // 
            txtBairro.Location = new Point(36, 590);
            txtBairro.Name = "txtBairro";
            txtBairro.Size = new Size(203, 23);
            txtBairro.TabIndex = 51;
            // 
            // lblBairro
            // 
            lblBairro.AutoSize = true;
            lblBairro.Location = new Point(36, 572);
            lblBairro.Name = "lblBairro";
            lblBairro.Size = new Size(41, 15);
            lblBairro.TabIndex = 50;
            lblBairro.Text = "Bairro:";
            // 
            // txtComplemento
            // 
            txtComplemento.Location = new Point(432, 546);
            txtComplemento.Name = "txtComplemento";
            txtComplemento.Size = new Size(100, 23);
            txtComplemento.TabIndex = 49;
            // 
            // lblComplemento
            // 
            lblComplemento.AutoSize = true;
            lblComplemento.Location = new Point(432, 528);
            lblComplemento.Name = "lblComplemento";
            lblComplemento.Size = new Size(87, 15);
            lblComplemento.TabIndex = 48;
            lblComplemento.Text = "Complemento:";
            // 
            // mtxtNumero
            // 
            mtxtNumero.Location = new Point(372, 546);
            mtxtNumero.Mask = "00000A";
            mtxtNumero.Name = "mtxtNumero";
            mtxtNumero.Size = new Size(54, 23);
            mtxtNumero.TabIndex = 47;
            // 
            // lblNumero
            // 
            lblNumero.AutoSize = true;
            lblNumero.Location = new Point(372, 528);
            lblNumero.Name = "lblNumero";
            lblNumero.Size = new Size(54, 15);
            lblNumero.TabIndex = 46;
            lblNumero.Text = "Número:";
            // 
            // txtLogradouro
            // 
            txtLogradouro.Location = new Point(36, 546);
            txtLogradouro.Name = "txtLogradouro";
            txtLogradouro.Size = new Size(330, 23);
            txtLogradouro.TabIndex = 45;
            // 
            // lblLogradouro
            // 
            lblLogradouro.AutoSize = true;
            lblLogradouro.Location = new Point(36, 528);
            lblLogradouro.Name = "lblLogradouro";
            lblLogradouro.Size = new Size(84, 15);
            lblLogradouro.TabIndex = 44;
            lblLogradouro.Text = "Rua / Avenida:";
            // 
            // btnBuscarCep
            // 
            btnBuscarCep.Location = new Point(142, 502);
            btnBuscarCep.Name = "btnBuscarCep";
            btnBuscarCep.Size = new Size(75, 23);
            btnBuscarCep.TabIndex = 43;
            btnBuscarCep.Text = "🔍 Buscar";
            btnBuscarCep.UseVisualStyleBackColor = true;
            btnBuscarCep.Click += btnBuscarCep_Click;
            // 
            // mtxtCep
            // 
            mtxtCep.Location = new Point(36, 502);
            mtxtCep.Mask = "00000-000";
            mtxtCep.Name = "mtxtCep";
            mtxtCep.Size = new Size(100, 23);
            mtxtCep.TabIndex = 42;
            // 
            // mtxtTel1
            // 
            mtxtTel1.Location = new Point(36, 458);
            mtxtTel1.Mask = "(00) 0,0000-0000";
            mtxtTel1.Name = "mtxtTel1";
            mtxtTel1.Size = new Size(100, 23);
            mtxtTel1.TabIndex = 41;
            // 
            // lblCep
            // 
            lblCep.AutoSize = true;
            lblCep.Location = new Point(36, 484);
            lblCep.Name = "lblCep";
            lblCep.Size = new Size(31, 15);
            lblCep.TabIndex = 40;
            lblCep.Text = "CEP:";
            // 
            // lblTel
            // 
            lblTel.AutoSize = true;
            lblTel.Location = new Point(36, 440);
            lblTel.Name = "lblTel";
            lblTel.Size = new Size(55, 15);
            lblTel.TabIndex = 39;
            lblTel.Text = "Telefone:";
            // 
            // mcDataNasc
            // 
            mcDataNasc.Location = new Point(36, 269);
            mcDataNasc.Name = "mcDataNasc";
            mcDataNasc.TabIndex = 38;
            // 
            // lblDataNasc
            // 
            lblDataNasc.AutoSize = true;
            lblDataNasc.Location = new Point(36, 245);
            lblDataNasc.Name = "lblDataNasc";
            lblDataNasc.Size = new Size(117, 15);
            lblDataNasc.TabIndex = 37;
            lblDataNasc.Text = "Data de Nascimento:";
            // 
            // txtEmail
            // 
            txtEmail.Location = new Point(36, 219);
            txtEmail.Name = "txtEmail";
            txtEmail.Size = new Size(227, 23);
            txtEmail.TabIndex = 36;
            // 
            // lblEmail
            // 
            lblEmail.AutoSize = true;
            lblEmail.Location = new Point(36, 201);
            lblEmail.Name = "lblEmail";
            lblEmail.Size = new Size(39, 15);
            lblEmail.TabIndex = 35;
            lblEmail.Text = "Email:";
            // 
            // mtxtCpf
            // 
            mtxtCpf.Location = new Point(36, 175);
            mtxtCpf.Mask = "000,000,000-00";
            mtxtCpf.Name = "mtxtCpf";
            mtxtCpf.Size = new Size(100, 23);
            mtxtCpf.TabIndex = 34;
            // 
            // lblCpf
            // 
            lblCpf.AutoSize = true;
            lblCpf.Location = new Point(36, 157);
            lblCpf.Name = "lblCpf";
            lblCpf.Size = new Size(31, 15);
            lblCpf.TabIndex = 33;
            lblCpf.Text = "CPF:";
            // 
            // txtNome
            // 
            txtNome.Location = new Point(36, 131);
            txtNome.Name = "txtNome";
            txtNome.Size = new Size(324, 23);
            txtNome.TabIndex = 32;
            // 
            // lblNome
            // 
            lblNome.AutoSize = true;
            lblNome.Location = new Point(36, 113);
            lblNome.Name = "lblNome";
            lblNome.Size = new Size(43, 15);
            lblNome.TabIndex = 31;
            lblNome.Text = "Nome:";
            // 
            // btnSalvar
            // 
            btnSalvar.Location = new Point(238, 663);
            btnSalvar.Name = "btnSalvar";
            btnSalvar.Size = new Size(75, 23);
            btnSalvar.TabIndex = 57;
            btnSalvar.Text = "💾 Salvar";
            btnSalvar.UseVisualStyleBackColor = true;
            btnSalvar.Click += btnSalvar_Click;
            // 
            // btnCancelar
            // 
            btnCancelar.Location = new Point(319, 663);
            btnCancelar.Name = "btnCancelar";
            btnCancelar.Size = new Size(75, 23);
            btnCancelar.TabIndex = 58;
            btnCancelar.Text = "Cancelar";
            btnCancelar.UseVisualStyleBackColor = true;
            btnCancelar.Click += btnCancelar_Click;
            // 
            // cboTipoUsuario
            // 
            cboTipoUsuario.FormattingEnabled = true;
            cboTipoUsuario.Location = new Point(36, 634);
            cboTipoUsuario.Name = "cboTipoUsuario";
            cboTipoUsuario.Size = new Size(121, 23);
            cboTipoUsuario.TabIndex = 59;
            // 
            // lblTipoUsuario
            // 
            lblTipoUsuario.AutoSize = true;
            lblTipoUsuario.Location = new Point(36, 616);
            lblTipoUsuario.Name = "lblTipoUsuario";
            lblTipoUsuario.Size = new Size(93, 15);
            lblTipoUsuario.TabIndex = 60;
            lblTipoUsuario.Text = "Tipo de Usuário:";
            // 
            // FrmAlterarCadastro
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(800, 726);
            Controls.Add(lblTipoUsuario);
            Controls.Add(cboTipoUsuario);
            Controls.Add(btnCancelar);
            Controls.Add(btnSalvar);
            Controls.Add(cboUf);
            Controls.Add(txtLocalidade);
            Controls.Add(lblLocalidade);
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
            Controls.Add(btnBuscar);
            Controls.Add(mtxtBuscar);
            Controls.Add(lblAlterarCadastro);
            FormBorderStyle = FormBorderStyle.FixedToolWindow;
            Name = "FrmAlterarCadastro";
            StartPosition = FormStartPosition.CenterScreen;
            Text = "Alterar Cadastro";
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private Label lblAlterarCadastro;
        private MaskedTextBox mtxtBuscar;
        private Button btnBuscar;
        private ComboBox cboUf;
        private TextBox txtLocalidade;
        private Label lblLocalidade;
        private Label lblUf;
        private TextBox txtBairro;
        private Label lblBairro;
        private TextBox txtComplemento;
        private Label lblComplemento;
        private MaskedTextBox mtxtNumero;
        private Label lblNumero;
        private TextBox txtLogradouro;
        private Label lblLogradouro;
        private Button btnBuscarCep;
        private MaskedTextBox mtxtCep;
        private MaskedTextBox mtxtTel1;
        private Label lblCep;
        private Label lblTel;
        private MonthCalendar mcDataNasc;
        private Label lblDataNasc;
        private TextBox txtEmail;
        private Label lblEmail;
        private MaskedTextBox mtxtCpf;
        private Label lblCpf;
        private TextBox txtNome;
        private Label lblNome;
        private Button btnSalvar;
        private Button btnCancelar;
        private ComboBox cboTipoUsuario;
        private Label lblTipoUsuario;
    }
}