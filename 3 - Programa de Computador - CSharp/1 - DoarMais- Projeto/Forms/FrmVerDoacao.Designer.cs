namespace DoarMais.Forms
{
    partial class FrmVerDoacao
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
            lblId = new Label();
            txtId = new TextBox();
            btnBuscar = new Button();
            lblNome = new Label();
            txtNome = new TextBox();
            lblCategoria = new Label();
            txtCategoria = new TextBox();
            lblSituacao = new Label();
            txtSituacao = new TextBox();
            picFoto = new PictureBox();
            btnFechar = new Button();
            btnAnterior = new Button();
            btnProximo = new Button();
            lblFoto = new Label();
            lblDescricao = new Label();
            txtDescricao = new TextBox();
            txtTitulo = new TextBox();
            txtCep = new TextBox();
            txtLogradouro = new TextBox();
            txtNumero = new TextBox();
            txtComplemento = new TextBox();
            txtBairro = new TextBox();
            txtLocalidade = new TextBox();
            txtUf = new TextBox();
            label1 = new Label();
            label2 = new Label();
            label3 = new Label();
            label4 = new Label();
            label5 = new Label();
            label6 = new Label();
            label7 = new Label();
            ((System.ComponentModel.ISupportInitialize)picFoto).BeginInit();
            SuspendLayout();
            // 
            // lblTitulos
            // 
            lblTitulos.AutoSize = true;
            lblTitulos.Font = new Font("Comic Sans MS", 15.75F, FontStyle.Bold, GraphicsUnit.Point, 0);
            lblTitulos.Location = new Point(12, 9);
            lblTitulos.Name = "lblTitulos";
            lblTitulos.Size = new Size(151, 30);
            lblTitulos.TabIndex = 4;
            lblTitulos.Text = "Ver a Doação";
            // 
            // lblId
            // 
            lblId.AutoSize = true;
            lblId.Location = new Point(12, 39);
            lblId.Name = "lblId";
            lblId.Size = new Size(77, 15);
            lblId.TabIndex = 5;
            lblId.Text = "ID da Doação";
            // 
            // txtId
            // 
            txtId.Location = new Point(12, 57);
            txtId.Name = "txtId";
            txtId.Size = new Size(100, 23);
            txtId.TabIndex = 6;
            // 
            // btnBuscar
            // 
            btnBuscar.Location = new Point(118, 57);
            btnBuscar.Name = "btnBuscar";
            btnBuscar.Size = new Size(75, 23);
            btnBuscar.TabIndex = 7;
            btnBuscar.Text = "🔍 Buscar";
            btnBuscar.UseVisualStyleBackColor = true;
            btnBuscar.Click += btnBuscar_Click;
            // 
            // lblNome
            // 
            lblNome.AutoSize = true;
            lblNome.Location = new Point(199, 39);
            lblNome.Name = "lblNome";
            lblNome.Size = new Size(50, 15);
            lblNome.TabIndex = 8;
            lblNome.Text = "Usuário:";
            // 
            // txtNome
            // 
            txtNome.Location = new Point(199, 58);
            txtNome.Name = "txtNome";
            txtNome.ReadOnly = true;
            txtNome.Size = new Size(257, 23);
            txtNome.TabIndex = 9;
            // 
            // lblCategoria
            // 
            lblCategoria.AutoSize = true;
            lblCategoria.Location = new Point(462, 39);
            lblCategoria.Name = "lblCategoria";
            lblCategoria.Size = new Size(61, 15);
            lblCategoria.TabIndex = 10;
            lblCategoria.Text = "Categoria:";
            // 
            // txtCategoria
            // 
            txtCategoria.Location = new Point(462, 57);
            txtCategoria.Name = "txtCategoria";
            txtCategoria.ReadOnly = true;
            txtCategoria.Size = new Size(119, 23);
            txtCategoria.TabIndex = 11;
            // 
            // lblSituacao
            // 
            lblSituacao.AutoSize = true;
            lblSituacao.Location = new Point(587, 39);
            lblSituacao.Name = "lblSituacao";
            lblSituacao.Size = new Size(52, 15);
            lblSituacao.TabIndex = 12;
            lblSituacao.Text = "Situação";
            // 
            // txtSituacao
            // 
            txtSituacao.Location = new Point(587, 57);
            txtSituacao.Name = "txtSituacao";
            txtSituacao.ReadOnly = true;
            txtSituacao.Size = new Size(131, 23);
            txtSituacao.TabIndex = 13;
            // 
            // picFoto
            // 
            picFoto.Location = new Point(368, 87);
            picFoto.Name = "picFoto";
            picFoto.Size = new Size(396, 298);
            picFoto.SizeMode = PictureBoxSizeMode.Zoom;
            picFoto.TabIndex = 14;
            picFoto.TabStop = false;
            // 
            // btnFechar
            // 
            btnFechar.Location = new Point(368, 452);
            btnFechar.Name = "btnFechar";
            btnFechar.Size = new Size(75, 23);
            btnFechar.TabIndex = 15;
            btnFechar.Text = "Fechar";
            btnFechar.UseVisualStyleBackColor = true;
            btnFechar.Click += btnFechar_Click;
            // 
            // btnAnterior
            // 
            btnAnterior.Location = new Point(506, 391);
            btnAnterior.Name = "btnAnterior";
            btnAnterior.Size = new Size(75, 23);
            btnAnterior.TabIndex = 16;
            btnAnterior.Text = "◀ Anterior";
            btnAnterior.UseVisualStyleBackColor = true;
            btnAnterior.Click += btnAnterior_Click;
            // 
            // btnProximo
            // 
            btnProximo.Location = new Point(587, 391);
            btnProximo.Name = "btnProximo";
            btnProximo.Size = new Size(75, 23);
            btnProximo.TabIndex = 17;
            btnProximo.Text = "Próximo ▶";
            btnProximo.UseVisualStyleBackColor = true;
            btnProximo.Click += btnProximo_Click;
            // 
            // lblFoto
            // 
            lblFoto.AutoSize = true;
            lblFoto.Location = new Point(476, 395);
            lblFoto.Name = "lblFoto";
            lblFoto.Size = new Size(24, 15);
            lblFoto.TabIndex = 18;
            lblFoto.Text = "1/3";
            // 
            // lblDescricao
            // 
            lblDescricao.AutoSize = true;
            lblDescricao.Location = new Point(12, 113);
            lblDescricao.Name = "lblDescricao";
            lblDescricao.Size = new Size(61, 15);
            lblDescricao.TabIndex = 19;
            lblDescricao.Text = "Descrição:";
            // 
            // txtDescricao
            // 
            txtDescricao.Location = new Point(12, 131);
            txtDescricao.Multiline = true;
            txtDescricao.Name = "txtDescricao";
            txtDescricao.ReadOnly = true;
            txtDescricao.ScrollBars = ScrollBars.Horizontal;
            txtDescricao.Size = new Size(350, 80);
            txtDescricao.TabIndex = 20;
            // 
            // txtTitulo
            // 
            txtTitulo.Location = new Point(12, 87);
            txtTitulo.Name = "txtTitulo";
            txtTitulo.ReadOnly = true;
            txtTitulo.Size = new Size(350, 23);
            txtTitulo.TabIndex = 21;
            // 
            // txtCep
            // 
            txtCep.Location = new Point(74, 217);
            txtCep.Name = "txtCep";
            txtCep.ReadOnly = true;
            txtCep.Size = new Size(100, 23);
            txtCep.TabIndex = 22;
            // 
            // txtLogradouro
            // 
            txtLogradouro.Location = new Point(74, 246);
            txtLogradouro.Multiline = true;
            txtLogradouro.Name = "txtLogradouro";
            txtLogradouro.ReadOnly = true;
            txtLogradouro.Size = new Size(288, 47);
            txtLogradouro.TabIndex = 23;
            // 
            // txtNumero
            // 
            txtNumero.Location = new Point(74, 299);
            txtNumero.Name = "txtNumero";
            txtNumero.ReadOnly = true;
            txtNumero.Size = new Size(100, 23);
            txtNumero.TabIndex = 24;
            // 
            // txtComplemento
            // 
            txtComplemento.Location = new Point(105, 328);
            txtComplemento.Name = "txtComplemento";
            txtComplemento.ReadOnly = true;
            txtComplemento.Size = new Size(257, 23);
            txtComplemento.TabIndex = 25;
            // 
            // txtBairro
            // 
            txtBairro.Location = new Point(74, 357);
            txtBairro.Name = "txtBairro";
            txtBairro.ReadOnly = true;
            txtBairro.Size = new Size(288, 23);
            txtBairro.TabIndex = 26;
            // 
            // txtLocalidade
            // 
            txtLocalidade.Location = new Point(74, 386);
            txtLocalidade.Name = "txtLocalidade";
            txtLocalidade.ReadOnly = true;
            txtLocalidade.Size = new Size(288, 23);
            txtLocalidade.TabIndex = 27;
            // 
            // txtUf
            // 
            txtUf.Location = new Point(74, 415);
            txtUf.Name = "txtUf";
            txtUf.ReadOnly = true;
            txtUf.Size = new Size(38, 23);
            txtUf.TabIndex = 28;
            // 
            // label1
            // 
            label1.AutoSize = true;
            label1.Location = new Point(12, 220);
            label1.Name = "label1";
            label1.Size = new Size(31, 15);
            label1.TabIndex = 29;
            label1.Text = "CEP:";
            // 
            // label2
            // 
            label2.AutoSize = true;
            label2.Location = new Point(10, 249);
            label2.Name = "label2";
            label2.Size = new Size(58, 15);
            label2.TabIndex = 30;
            label2.Text = "Rua / Av.:";
            // 
            // label3
            // 
            label3.AutoSize = true;
            label3.Location = new Point(12, 302);
            label3.Name = "label3";
            label3.Size = new Size(54, 15);
            label3.TabIndex = 31;
            label3.Text = "Número:";
            // 
            // label4
            // 
            label4.AutoSize = true;
            label4.Location = new Point(12, 331);
            label4.Name = "label4";
            label4.Size = new Size(87, 15);
            label4.TabIndex = 32;
            label4.Text = "Complemento:";
            // 
            // label5
            // 
            label5.AutoSize = true;
            label5.Location = new Point(13, 360);
            label5.Name = "label5";
            label5.Size = new Size(41, 15);
            label5.TabIndex = 33;
            label5.Text = "Bairro:";
            // 
            // label6
            // 
            label6.AutoSize = true;
            label6.Location = new Point(13, 389);
            label6.Name = "label6";
            label6.Size = new Size(47, 15);
            label6.TabIndex = 34;
            label6.Text = "Cidade:";
            // 
            // label7
            // 
            label7.AutoSize = true;
            label7.Location = new Point(13, 418);
            label7.Name = "label7";
            label7.Size = new Size(45, 15);
            label7.TabIndex = 35;
            label7.Text = "Estado:";
            // 
            // FrmVerDoacao
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(800, 487);
            Controls.Add(label7);
            Controls.Add(label6);
            Controls.Add(label5);
            Controls.Add(label4);
            Controls.Add(label3);
            Controls.Add(label2);
            Controls.Add(label1);
            Controls.Add(txtUf);
            Controls.Add(txtLocalidade);
            Controls.Add(txtBairro);
            Controls.Add(txtComplemento);
            Controls.Add(txtNumero);
            Controls.Add(txtLogradouro);
            Controls.Add(txtCep);
            Controls.Add(txtTitulo);
            Controls.Add(txtDescricao);
            Controls.Add(lblDescricao);
            Controls.Add(lblFoto);
            Controls.Add(btnProximo);
            Controls.Add(btnAnterior);
            Controls.Add(btnFechar);
            Controls.Add(picFoto);
            Controls.Add(txtSituacao);
            Controls.Add(lblSituacao);
            Controls.Add(txtCategoria);
            Controls.Add(lblCategoria);
            Controls.Add(txtNome);
            Controls.Add(lblNome);
            Controls.Add(btnBuscar);
            Controls.Add(txtId);
            Controls.Add(lblId);
            Controls.Add(lblTitulos);
            FormBorderStyle = FormBorderStyle.FixedToolWindow;
            Name = "FrmVerDoacao";
            StartPosition = FormStartPosition.CenterScreen;
            Text = "Ver a Doação";
            ((System.ComponentModel.ISupportInitialize)picFoto).EndInit();
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private Label lblTitulos;
        private Label lblId;
        private TextBox txtId;
        private Button btnBuscar;
        private Label lblNome;
        private TextBox txtNome;
        private Label lblCategoria;
        private TextBox txtCategoria;
        private Label lblSituacao;
        private TextBox txtSituacao;
        private PictureBox picFoto;
        private Button btnFechar;
        private Button btnAnterior;
        private Button btnProximo;
        private Label lblFoto;
        private Label lblDescricao;
        private TextBox txtDescricao;
        private TextBox txtTitulo;
        private TextBox txtCep;
        private TextBox txtLogradouro;
        private TextBox txtNumero;
        private TextBox txtComplemento;
        private TextBox txtBairro;
        private TextBox txtLocalidade;
        private TextBox txtUf;
        private Label label1;
        private Label label2;
        private Label label3;
        private Label label4;
        private Label label5;
        private Label label6;
        private Label label7;
    }
}