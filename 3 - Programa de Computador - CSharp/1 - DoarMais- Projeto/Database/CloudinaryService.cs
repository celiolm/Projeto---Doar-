using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using CloudinaryDotNet;
using CloudinaryDotNet.Actions;

namespace DoarMais.Database
{
    public class CloudinaryService
    {
        private readonly Cloudinary _cloudinary;

        public CloudinaryService()
        {
            var account = new Account(
                "doarmais",
                "1234",
                "1234"
            );
            _cloudinary = new Cloudinary(account);
        }

        public string EnviarFoto(string caminhoLocal)
        {
            var uploadParams = new ImageUploadParams
            {
                File = new FileDescription(caminhoLocal),
                Folder = "doarmais"
            };

            var resultado = _cloudinary.Upload(uploadParams);
            return resultado.SecureUrl.ToString();
        }
    }
}