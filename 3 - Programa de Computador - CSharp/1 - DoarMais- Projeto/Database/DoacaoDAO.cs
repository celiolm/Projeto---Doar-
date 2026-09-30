using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using DoarMais.Models;
using MySql.Data.MySqlClient;

namespace DoarMais.Database
{
    public class DoacaoDAO
    {
        public int Inserir(Doacao d)
        {
            using var con = Conexao.ObterConexao();
            con.Open();

            string query = @"INSERT INTO doacao 
        (id_usuario, titulo, categoria, descricao, cep, logradouro, numero, 
         complemento, bairro, localidade, uf, situacao)
        VALUES 
        (@idUsuario, @titulo, @categoria, @descricao, @cep, @logradouro, @numero,
         @complemento, @bairro, @localidade, @uf, @situacao)";

            using var cmd = new MySqlCommand(query, con);
            cmd.Parameters.AddWithValue("@idUsuario", d.IdUsuario);
            cmd.Parameters.AddWithValue("@titulo", d.Titulo);
            cmd.Parameters.AddWithValue("@categoria", d.Categoria);
            cmd.Parameters.AddWithValue("@descricao", d.Descricao);
            cmd.Parameters.AddWithValue("@cep", d.Cep);
            cmd.Parameters.AddWithValue("@logradouro", d.Logradouro);
            cmd.Parameters.AddWithValue("@numero", d.Numero);
            cmd.Parameters.AddWithValue("@complemento", d.Complemento);
            cmd.Parameters.AddWithValue("@bairro", d.Bairro);
            cmd.Parameters.AddWithValue("@localidade", d.Localidade);
            cmd.Parameters.AddWithValue("@uf", d.Uf);
            cmd.Parameters.AddWithValue("@situacao", "Pendente");

            cmd.ExecuteNonQuery();
            return (int)cmd.LastInsertedId;
        }

        public List<Doacao> ListarTodas()
        {
            using var con = Conexao.ObterConexao();
            con.Open();

            string query = @"SELECT d.*, u.nome FROM doacao d
                     INNER JOIN usuario u ON d.id_usuario = u.id_usuario
                     ORDER BY d.id_doacao DESC";

            using var cmd = new MySqlCommand(query, con);
            using var reader = cmd.ExecuteReader();

            var lista = new List<Doacao>();
            while (reader.Read())
            {
                lista.Add(new Doacao
                {
                    IdDoacao = Convert.ToInt32(reader["id_doacao"]),
                    IdUsuario = Convert.ToInt32(reader["id_usuario"]),
                    Titulo = reader["titulo"].ToString()!,
                    Categoria = reader["categoria"].ToString()!,
                    Descricao = reader["descricao"].ToString()!,
                    Cep = reader["cep"].ToString()!,
                    Logradouro = reader["logradouro"].ToString()!,
                    Numero = reader["numero"].ToString()!,
                    Complemento = reader["complemento"].ToString()!,
                    Bairro = reader["bairro"].ToString()!,
                    Localidade = reader["localidade"].ToString()!,
                    Uf = reader["uf"].ToString()!,
                    Situacao = reader["situacao"].ToString()!,
                    NomeUsuario = reader["nome"].ToString()!
                });
            }
            return lista;
        }

        public void AtualizarSituacao(int idDoacao, string situacao)
        {
            using var con = Conexao.ObterConexao();
            con.Open();

            string query = "UPDATE doacao SET situacao = @situacao WHERE id_doacao = @id";
            using var cmd = new MySqlCommand(query, con);
            cmd.Parameters.AddWithValue("@situacao", situacao);
            cmd.Parameters.AddWithValue("@id", idDoacao);

            cmd.ExecuteNonQuery();
        }

        public void Deletar(int idDoacao)
        {
            using var con = Conexao.ObterConexao();
            con.Open();

            string query = "DELETE FROM doacao WHERE id_doacao = @id";
            using var cmd = new MySqlCommand(query, con);
            cmd.Parameters.AddWithValue("@id", idDoacao);

            cmd.ExecuteNonQuery();
        }

        public Doacao? BuscarPorId(int id)
        {
            using var con = Conexao.ObterConexao();
            con.Open();

            string query = @"SELECT d.*, u.nome FROM doacao d
                     INNER JOIN usuario u ON d.id_usuario = u.id_usuario
                     WHERE d.id_doacao = @id";

            using var cmd = new MySqlCommand(query, con);
            cmd.Parameters.AddWithValue("@id", id);

            using var reader = cmd.ExecuteReader();
            if (reader.Read())
            {
                return new Doacao
                {
                    IdDoacao = Convert.ToInt32(reader["id_doacao"]),
                    IdUsuario = Convert.ToInt32(reader["id_usuario"]),
                    Titulo = reader["titulo"].ToString()!,
                    Categoria = reader["categoria"].ToString()!,
                    Descricao = reader["descricao"].ToString()!,
                    Cep = reader["cep"].ToString()!,
                    Logradouro = reader["logradouro"].ToString()!,
                    Numero = reader["numero"].ToString()!,
                    Complemento = reader["complemento"].ToString()!,
                    Bairro = reader["bairro"].ToString()!,
                    Localidade = reader["localidade"].ToString()!,
                    Uf = reader["uf"].ToString()!,
                    Situacao = reader["situacao"].ToString()!,
                    NomeUsuario = reader["nome"].ToString()!
                };
            }
            return null;
        }

        public List<string> BuscarFotos(int idDoacao)
        {
            using var con = Conexao.ObterConexao();
            con.Open();

            string query = @"SELECT caminho FROM doacao_fotos
                     WHERE id_doacao = @id
                     ORDER BY ordem";

            using var cmd = new MySqlCommand(query, con);
            cmd.Parameters.AddWithValue("@id", idDoacao);

            using var reader = cmd.ExecuteReader();
            var fotos = new List<string>();
            while (reader.Read())
                fotos.Add(reader["caminho"].ToString()!);

            return fotos;
        }

        public void InserirFoto(int idDoacao, string caminho, int ordem)
        {
            using var con = Conexao.ObterConexao();
            con.Open();

            string query = @"INSERT INTO doacao_fotos (id_doacao, caminho, ordem)
                     VALUES (@idDoacao, @caminho, @ordem)";

            using var cmd = new MySqlCommand(query, con);
            cmd.Parameters.AddWithValue("@idDoacao", idDoacao);
            cmd.Parameters.AddWithValue("@caminho", caminho);
            cmd.Parameters.AddWithValue("@ordem", ordem);

            cmd.ExecuteNonQuery();
        }
    }
}