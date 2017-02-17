using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using Newtonsoft.Json;
using waiter.Properties;

namespace waiter
{
    public class Restaurant
    {
        public readonly string RestApiUrl = "http://myrestaurantsoftware.dev/rest_server/web/app_dev.php/api/";
        private readonly WebClient _webClient;

        public Restaurant()
        {
            _webClient = new WebClient();
            _webClient.Headers.Add("user-agent", "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.0.3705;)");
        }

        public bool Log_In(string login, string password)
        {
            try
            {
                _webClient.Headers.Add("Content-Type", "application/json");
                var status = _webClient.UploadString(RestApiUrl + "login", "POST", "{\"username\": \"" + login + "\",\"password\": \"" + password + "\"}");
                var token = JsonConvert.DeserializeObject<RestToken>(status);
                Settings.Default.token = token.token;
                Settings.Default.Save();
//                _webClient.Headers.Add("Authorization", "Bearer  {{jwtToken}}");
                return true;
            }
            catch (Exception e)
            {
                MessageBox.Show(e.Message.ToString());
            }
            return false;
        }

        public Dictionary<string, string> GetCategory()
        {
            _webClient.Headers.Add("Authorization", "Bearer  " + Settings.Default.token);
            var response = _webClient.DownloadString(RestApiUrl + "category");
            return JsonConvert.DeserializeObject<Dictionary<string, string>>(response);
        }
    }
}
