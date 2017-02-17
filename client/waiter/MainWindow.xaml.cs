using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using Newtonsoft.Json;
using waiter.Properties;

namespace waiter
{
    /// <summary>
    /// Logika interakcji dla klasy MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        private Restaurant _restaurant = new Restaurant();

        public MainWindow()
        {
            InitializeComponent();

            if (Settings.Default.token != "")
            {
                GenerateCategory();
                return;
            }

            var loginWindow = new login(_restaurant);
            loginWindow.Show();
            this.Close();
        }

        private void GenerateCategory()
        {
//            _restaurant.GetCategory();
            MessageBox.Show(_restaurant.GetCategory().ToString());
        }
    }
}
