using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MySql.Data.MySqlClient;

namespace DoarMais.Database
{
    public class Conexao
    {
        private const string StringConexao =
            "Server=127.0.0.1;Port=3306;Database=doarmais;Uid=1234;Pwd=1234;SslMode=Required;SslCa=ca.pem;";

        public static MySqlConnection ObterConexao()
        {
            return new MySqlConnection(StringConexao);
        }
    }
}