using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using DoarMais.Database;
using DoarMais.Models;
using MySql.Data.MySqlClient;
using BCrypt.Net;

namespace DoarMais.Database
{
    public class UsuarioDAO
    {
        public Usuario? Login(string email, string senha)
        {
            using var con = Conexao.ObterConexao();
            con.Open();

            string query = "SELECT * FROM usuario WHERE email = @email";
            using var cmd = new MySqlCommand(query, con);
            cmd.Parameters.AddWithValue("@email", email);

            using var reader = cmd.ExecuteReader();
            if (reader.Read())
            {
                string hashSalvo = reader["senha"].ToString()!;
                if (BCrypt.Net.BCrypt.Verify(senha, hashSalvo))
                {
                    return new Usuario
                    {
                        IdUsuario = Convert.ToInt32(reader["id_usuario"]),
                        Nome = reader["nome"].ToString()!,
                        Email = reader["email"].ToString()!,
                        TipoUsuario = reader["tipo_usuario"].ToString()!
                    };
                }
            }
            return null;
        }

        public Usuario? BuscarPorCpf(string cpf)
        {
            using var con = Conexao.ObterConexao();
            con.Open();

            string query = "SELECT * FROM usuario WHERE cpf = @cpf";
            using var cmd = new MySqlCommand(query, con);
            cmd.Parameters.AddWithValue("@cpf", cpf);

            using var reader = cmd.ExecuteReader();
            if (reader.Read())
            {
                return new Usuario
                {
                    IdUsuario = Convert.ToInt32(reader["id_usuario"]),
                    Nome = reader["nome"].ToString()!,
                    Cpf = reader["cpf"].ToString()!,
                    Email = reader["email"].ToString()!,
                    DataNasc = Convert.ToDateTime(reader["data_nasc"]),
                    Tel = reader["tel"].ToString()!,
                    Cep = reader["cep"].ToString()!,
                    Logradouro = reader["logradouro"].ToString()!,
                    Numero = reader["numero"].ToString()!,
                    Complemento = reader["complemento"].ToString()!,
                    Bairro = reader["bairro"].ToString()!,
                    Localidade = reader["localidade"].ToString()!,
                    Uf = reader["uf"].ToString()!,
                    TipoUsuario = reader["tipo_usuario"].ToString()!
                };
            }
            return null;
        }

        public void Atualizar(Usuario u)
        {
            using var con = Conexao.ObterConexao();
            con.Open();

            string query = @"UPDATE usuario SET
        nome        = @nome,
        email       = @email,
        data_nasc   = @dataNasc,
        tel         = @tel,
        cep         = @cep,
        logradouro  = @logradouro,
        numero      = @numero,
        complemento = @complemento,
        bairro      = @bairro,
        localidade  = @localidade,
        uf          = @uf
        tipo_usuario = @tipoUsuario
        WHERE id_usuario = @id";

            using var cmd = new MySqlCommand(query, con);
            cmd.Parameters.AddWithValue("@nome", u.Nome);
            cmd.Parameters.AddWithValue("@email", u.Email);
            cmd.Parameters.AddWithValue("@dataNasc", u.DataNasc.ToString("yyyy-MM-dd"));
            cmd.Parameters.AddWithValue("@tel", u.Tel);
            cmd.Parameters.AddWithValue("@cep", u.Cep);
            cmd.Parameters.AddWithValue("@logradouro", u.Logradouro);
            cmd.Parameters.AddWithValue("@numero", u.Numero);
            cmd.Parameters.AddWithValue("@complemento", u.Complemento);
            cmd.Parameters.AddWithValue("@bairro", u.Bairro);
            cmd.Parameters.AddWithValue("@localidade", u.Localidade);
            cmd.Parameters.AddWithValue("@uf", u.Uf);
            cmd.Parameters.AddWithValue("@tipoUsuario", u.TipoUsuario);
            cmd.Parameters.AddWithValue("@id", u.IdUsuario);

            cmd.ExecuteNonQuery();
        }

        public void Inserir(Usuario u)
        {
            using var con = Conexao.ObterConexao();
            con.Open();

            string query = @"INSERT INTO usuario 
        (nome, cpf, email, data_nasc, tel, cep, logradouro, numero,
         complemento, bairro, localidade, uf, senha, tipo_usuario)
        VALUES
        (@nome, @cpf, @email, @dataNasc, @tel, @cep, @logradouro, @numero,
         @complemento, @bairro, @localidade, @uf, @senha, @tipoUsuario)";

            using var cmd = new MySqlCommand(query, con);
            cmd.Parameters.AddWithValue("@nome", u.Nome);
            cmd.Parameters.AddWithValue("@cpf", u.Cpf);
            cmd.Parameters.AddWithValue("@email", u.Email);
            cmd.Parameters.AddWithValue("@dataNasc", u.DataNasc.ToString("yyyy-MM-dd"));
            cmd.Parameters.AddWithValue("@tel", u.Tel);
            cmd.Parameters.AddWithValue("@cep", u.Cep);
            cmd.Parameters.AddWithValue("@logradouro", u.Logradouro);
            cmd.Parameters.AddWithValue("@numero", u.Numero);
            cmd.Parameters.AddWithValue("@complemento", u.Complemento);
            cmd.Parameters.AddWithValue("@bairro", u.Bairro);
            cmd.Parameters.AddWithValue("@localidade", u.Localidade);
            cmd.Parameters.AddWithValue("@uf", u.Uf);
            cmd.Parameters.AddWithValue("@senha", BCrypt.Net.BCrypt.HashPassword(u.Senha));
            cmd.Parameters.AddWithValue("@tipoUsuario", u.TipoUsuario);

            cmd.ExecuteNonQuery();
        }
    }
}