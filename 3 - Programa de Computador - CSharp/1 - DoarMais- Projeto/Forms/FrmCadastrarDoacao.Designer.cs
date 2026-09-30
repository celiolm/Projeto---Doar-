namespace DoarMais.Forms
{
    partial class FrmCadastrarDoacao
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
            lblTitulos = new Label();
            lblUsuario = new Label();
            mtxtCpf = new MaskedTextBox();
            btnBuscarUsuario = new Button();
            lblNomeUsuario = new Label();
            lblNomeUsuario1 = new Label();
            lblCategoria = new Label();
            cboCategoria = new ComboBox();
            btnSalvar = new Button();
            btnCancelar = new Button();
            lblDescricao = new Label();
            txtDescricao = new TextBox();
            btnAdicionarFoto = new Button();
            lstFotos = new ListBox();
            lblTitulo = new Label();
            txtTitulo = new TextBox();
            lblCep = new Label();
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
            lblLocalidade = new Label();
            txtLocalidade = new TextBox();
            lblUf = new Label();
            cboUf = new ComboBox();
            SuspendLayout();
            // 
            // lblTitulos
            // 
            lblTitulos.AutoSize = true;
            lblTitulos.Font = new Font("Comic Sans MS", 15.75F, FontStyle.Bold, GraphicsUnit.Point, 0);
            lblTitulos.Location = new Point(12, 9);
            lblTitulos.Name = "lblTitulos";
            lblTitulos.Size = new Size(195, 30);
            lblTitulos.TabIndex = 1;
            lblTitulos.Text = "Cadastrar Doação";
            // 
            // lblUsuario
            // 
            lblUsuario.AutoSize = true;
            lblUsuario.Location = new Point(16, 49);
            lblUsuario.Name = "lblUsuario";
            lblUsuario.Size = new Size(91, 15);
            lblUsuario.TabIndex = 2;
            lblUsuario.Text = "CPF do Usuário:";
            // 
            // mtxtCpf
            // 
            mtxtCpf.Location = new Point(16, 67);
            mtxtCpf.Mask = "000,000,000-00";
            mtxtCpf.Name = "mtxtCpf";
            mtxtCpf.Size = new Size(100, 23);
            mtxtCpf.TabIndex = 3;
            // 
            // btnBuscarUsuario
            // 
            btnBuscarUsuario.Location = new Point(122, 67);
            btnBuscarUsuario.Name = "btnBuscarUsuario";
            btnBuscarUsuario.Size = new Size(75, 23);
            btnBuscarUsuario.TabIndex = 4;
            btnBuscarUsuario.Text = "🔍 Buscar";
            btnBuscarUsuario.UseVisualStyleBackColor = true;
            btnBuscarUsuario.Click += btnBuscarUsuario_Click;
            // 
            // lblNomeUsuario
            // 
            lblNomeUsuario.AutoSize = true;
            lblNomeUsuario.Location = new Point(203, 71);
            lblNomeUsuario.Name = "lblNomeUsuario";
            lblNomeUsuario.Size = new Size(12, 15);
            lblNomeUsuario.TabIndex = 5;
            lblNomeUsuario.Text = "-";
            // 
            // lblNomeUsuario1
            // 
            lblNomeUsuario1.AutoSize = true;
            lblNomeUsuario1.Location = new Point(203, 49);
            lblNomeUsuario1.Name = "lblNomeUsuario1";
            lblNomeUsuario1.Size = new Size(103, 15);
            lblNomeUsuario1.TabIndex = 6;
            lblNomeUsuario1.Text = "Nome do Usuário:";
            // 
            // lblCategoria
            // 
            lblCategoria.AutoSize = true;
            lblCategoria.Location = new Point(16, 145);
            lblCategoria.Name = "lblCategoria";
            lblCategoria.Size = new Size(61, 15);
            lblCategoria.TabIndex = 7;
            lblCategoria.Text = "Categoria:";
            // 
            // cboCategoria
            // 
            cboCategoria.FormattingEnabled = true;
            cboCategoria.Location = new Point(83, 142);
            cboCategoria.Name = "cboCategoria";
            cboCategoria.Size = new Size(280, 23);
            cboCategoria.TabIndex = 8;
            // 
            // btnSalvar
            // 
            btnSalvar.Location = new Point(274, 457);
            btnSalvar.Name = "btnSalvar";
            btnSalvar.Size = new Size(75, 23);
            btnSalvar.TabIndex = 9;
            btnSalvar.Text = "💾 Salvar";
            btnSalvar.UseVisualStyleBackColor = true;
            btnSalvar.Click += btnSalvar_Click;
            // 
            // btnCancelar
            // 
            btnCancelar.Location = new Point(355, 457);
            btnCancelar.Name = "btnCancelar";
            btnCancelar.Size = new Size(75, 23);
            btnCancelar.TabIndex = 10;
            btnCancelar.Text = "Cancelar";
            btnCancelar.UseVisualStyleBackColor = true;
            btnCancelar.Click += btnCancelar_Click;
            // 
            // lblDescricao
            // 
            lblDescricao.AutoSize = true;
            lblDescricao.Location = new Point(16, 178);
            lblDescricao.Name = "lblDescricao";
            lblDescricao.Size = new Size(61, 15);
            lblDescricao.TabIndex = 11;
            lblDescricao.Text = "Descrição:";
            // 
            // txtDescricao
            // 
            txtDescricao.Location = new Point(16, 198);
            txtDescricao.Multiline = true;
            txtDescricao.Name = "txtDescricao";
            txtDescricao.PlaceholderText = "Descreva o item, estado de conservação, detalhes importantes...";
            txtDescricao.Size = new Size(347, 114);
            txtDescricao.TabIndex = 12;
            // 
            // btnAdicionarFoto
            // 
            btnAdicionarFoto.Location = new Point(291, 418);
            btnAdicionarFoto.Name = "btnAdicionarFoto";
            btnAdicionarFoto.Size = new Size(118, 23);
            btnAdicionarFoto.TabIndex = 13;
            btnAdicionarFoto.Text = "📷 Adicionar Fotos";
            btnAdicionarFoto.UseVisualStyleBackColor = true;
            btnAdicionarFoto.Click += btnAdicionarFoto_Click;
            // 
            // lstFotos
            // 
            lstFotos.FormattingEnabled = true;
            lstFotos.ItemHeight = 15;
            lstFotos.Location = new Point(168, 318);
            lstFotos.Name = "lstFotos";
            lstFotos.Size = new Size(364, 94);
            lstFotos.TabIndex = 14;
            // 
            // lblTitulo
            // 
            lblTitulo.AutoSize = true;
            lblTitulo.Location = new Point(16, 110);
            lblTitulo.Name = "lblTitulo";
            lblTitulo.Size = new Size(41, 15);
            lblTitulo.TabIndex = 15;
            lblTitulo.Text = "Título:";
            // 
            // txtTitulo
            // 
            txtTitulo.Location = new Point(63, 107);
            txtTitulo.Name = "txtTitulo";
            txtTitulo.PlaceholderText = "Ex: Sofá em bom estado, livros infantis...";
            txtTitulo.Size = new Size(300, 23);
            txtTitulo.TabIndex = 16;
            // 
            // lblCep
            // 
            lblCep.AutoSize = true;
            lblCep.Location = new Point(399, 49);
            lblCep.Name = "lblCep";
            lblCep.Size = new Size(31, 15);
            lblCep.TabIndex = 17;
            lblCep.Text = "CEP:";
            // 
            // mtxtCep
            // 
            mtxtCep.Location = new Point(399, 67);
            mtxtCep.Mask = "00000-000";
            mtxtCep.Name = "mtxtCep";
            mtxtCep.Size = new Size(100, 23);
            mtxtCep.TabIndex = 18;
            // 
            // btnBuscarCep
            // 
            btnBuscarCep.Location = new Point(505, 67);
            btnBuscarCep.Name = "btnBuscarCep";
            btnBuscarCep.Size = new Size(75, 23);
            btnBuscarCep.TabIndex = 19;
            btnBuscarCep.Text = "🔍 Buscar";
            btnBuscarCep.UseVisualStyleBackColor = true;
            btnBuscarCep.Click += btnBuscarCep_Click;
            // 
            // lblLogradouro
            // 
            lblLogradouro.AutoSize = true;
            lblLogradouro.Location = new Point(399, 93);
            lblLogradouro.Name = "lblLogradouro";
            lblLogradouro.Size = new Size(72, 15);
            lblLogradouro.TabIndex = 20;
            lblLogradouro.Text = "Logradouro:";
            // 
            // txtLogradouro
            // 
            txtLogradouro.Location = new Point(399, 111);
            txtLogradouro.Name = "txtLogradouro";
            txtLogradouro.Size = new Size(296, 23);
            txtLogradouro.TabIndex = 21;
            // 
            // lblNumero
            // 
            lblNumero.AutoSize = true;
            lblNumero.Location = new Point(399, 137);
            lblNumero.Name = "lblNumero";
            lblNumero.Size = new Size(54, 15);
            lblNumero.TabIndex = 22;
            lblNumero.Text = "Número:";
            // 
            // mtxtNumero
            // 
            mtxtNumero.Location = new Point(399, 155);
            mtxtNumero.Mask = "00000";
            mtxtNumero.Name = "mtxtNumero";
            mtxtNumero.Size = new Size(54, 23);
            mtxtNumero.TabIndex = 23;
            // 
            // lblComplemento
            // 
            lblComplemento.AutoSize = true;
            lblComplemento.Location = new Point(459, 137);
            lblComplemento.Name = "lblComplemento";
            lblComplemento.Size = new Size(87, 15);
            lblComplemento.TabIndex = 24;
            lblComplemento.Text = "Complemento:";
            // 
            // txtComplemento
            // 
            txtComplemento.Location = new Point(459, 155);
            txtComplemento.Name = "txtComplemento";
            txtComplemento.Size = new Size(236, 23);
            txtComplemento.TabIndex = 25;
            // 
            // lblBairro
            // 
            lblBairro.AutoSize = true;
            lblBairro.Location = new Point(399, 183);
            lblBairro.Name = "lblBairro";
            lblBairro.Size = new Size(41, 15);
            lblBairro.TabIndex = 26;
            lblBairro.Text = "Bairro:";
            // 
            // txtBairro
            // 
            txtBairro.Location = new Point(399, 201);
            txtBairro.Name = "txtBairro";
            txtBairro.Size = new Size(296, 23);
            txtBairro.TabIndex = 27;
            // 
            // lblLocalidade
            // 
            lblLocalidade.AutoSize = true;
            lblLocalidade.Location = new Point(399, 227);
            lblLocalidade.Name = "lblLocalidade";
            lblLocalidade.Size = new Size(47, 15);
            lblLocalidade.TabIndex = 28;
            lblLocalidade.Text = "Cidade:";
            // 
            // txtLocalidade
            // 
            txtLocalidade.Location = new Point(399, 245);
            txtLocalidade.Name = "txtLocalidade";
            txtLocalidade.Size = new Size(296, 23);
            txtLocalidade.TabIndex = 29;
            // 
            // lblUf
            // 
            lblUf.AutoSize = true;
            lblUf.Location = new Point(399, 271);
            lblUf.Name = "lblUf";
            lblUf.Size = new Size(45, 15);
            lblUf.TabIndex = 30;
            lblUf.Text = "Estado:";
            // 
            // cboUf
            // 
            cboUf.FormattingEnabled = true;
            cboUf.Location = new Point(399, 289);
            cboUf.Name = "cboUf";
            cboUf.Size = new Size(72, 23);
            cboUf.TabIndex = 31;
            // 
            // FrmCadastrarDoacao
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(707, 501);
            Controls.Add(cboUf);
            Controls.Add(lblUf);
            Controls.Add(txtLocalidade);
            Controls.Add(lblLocalidade);
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
            Controls.Add(lblCep);
            Controls.Add(txtTitulo);
            Controls.Add(lblTitulo);
            Controls.Add(lstFotos);
            Controls.Add(btnAdicionarFoto);
            Controls.Add(txtDescricao);
            Controls.Add(lblDescricao);
            Controls.Add(btnCancelar);
            Controls.Add(btnSalvar);
            Controls.Add(cboCategoria);
            Controls.Add(lblCategoria);
            Controls.Add(lblNomeUsuario1);
            Controls.Add(lblNomeUsuario);
            Controls.Add(btnBuscarUsuario);
            Controls.Add(mtxtCpf);
            Controls.Add(lblUsuario);
            Controls.Add(lblTitulos);
            FormBorderStyle = FormBorderStyle.FixedToolWindow;
            Name = "FrmCadastrarDoacao";
            StartPosition = FormStartPosition.CenterScreen;
            Text = "Cadastrar Doação";
            Load += FrmCadastrarDoacao_Load;
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private Label lblTitulos;
        private Label lblUsuario;
        private MaskedTextBox mtxtCpf;
        private Button btnBuscarUsuario;
        private Label lblNomeUsuario;
        private Label lblNomeUsuario1;
        private Label lblCategoria;
        private ComboBox cboCategoria;
        private Button btnSalvar;
        private Button btnCancelar;
        private Label lblDescricao;
        private TextBox txtDescricao;
        private Button btnAdicionarFoto;
        private ListBox lstFotos;
        private Label lblTitulo;
        private TextBox txtTitulo;
        private Label lblCep;
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
        private Label lblLocalidade;
        private TextBox txtLocalidade;
        private Label lblUf;
        private ComboBox cboUf;
    }
}