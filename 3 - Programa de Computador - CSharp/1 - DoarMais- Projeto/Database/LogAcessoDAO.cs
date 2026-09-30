using DoarMais.Database;
using MySql.Data.MySqlClient;
using System.Net;
using System.Windows.Forms;

namespace DoarMais.Database
{
    public class LogAcessoDAO
    {
        public static void Registrar(int idUsuario)
        {
            string ip = ObterIp();
            string dispositivo = ObterDispositivo();

            using var con = Conexao.ObterConexao();
            con.Open();

            string query = @"INSERT INTO log_acesso (id_usuario, ip, dispositivo, data_hora)
                             VALUES (@idUsuario, @ip, @dispositivo, NOW())";

            using var cmd = new MySqlCommand(query, con);
            cmd.Parameters.AddWithValue("@idUsuario", idUsuario);
            cmd.Parameters.AddWithValue("@ip", ip);
            cmd.Parameters.AddWithValue("@dispositivo", dispositivo);

            cmd.ExecuteNonQuery();
        }

        private static string ObterIp()
        {
            try
            {
                foreach (var ip in Dns.GetHostEntry(Dns.GetHostName()).AddressList)
                {
                    if (ip.AddressFamily == System.Net.Sockets.AddressFamily.InterNetwork)
                        return ip.ToString();
                }
            }
            catch { }
            return "Desconhecido";
        }

        private static string ObterDispositivo()
        {
            try
            {
                string os = Environment.OSVersion.VersionString;
                var battery = SystemInformation.PowerStatus;

                string tipo = battery.BatteryChargeStatus != BatteryChargeStatus.NoSystemBattery
                    ? "Notebook"
                    : "PC";

                return $"{tipo} ({os})";
            }
            catch { }
            return "Desconhecido";
        }
    }
}