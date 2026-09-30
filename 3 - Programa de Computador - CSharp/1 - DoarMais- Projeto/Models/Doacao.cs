using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DoarMais.Models
{
    public class Doacao
    {
        public int IdDoacao { get; set; }
        public int IdUsuario { get; set; }
        public string Categoria { get; set; }
        public string Descricao { get; set; }
        public string Situacao { get; set; }
        public string NomeUsuario { get; set; }
        public string Titulo { get; set; }
        public string Cep { get; set; }
        public string Logradouro { get; set; }
        public string Numero { get; set; }
        public string Complemento { get; set; }
        public string Bairro { get; set; }
        public string Localidade { get; set; }
        public string Uf { get; set; }
    }
}