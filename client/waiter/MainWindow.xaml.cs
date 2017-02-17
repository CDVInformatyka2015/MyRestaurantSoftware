using System;
using System.Collections.Generic;
using System.Configuration;
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

            if (Settings.Default.token == "")
            {
                var loginWindow = new login(_restaurant);
                loginWindow.Show();
                this.Close();
                return;
            }

            GenerateCategory();
        }

        private void GenerateCategory()
        {
            var categorys = _restaurant.GetCategory();

            foreach (var item in categorys)
            {
                ListaKategorii.Items.Add(item["name"]);
            }
        }

        private void ListaKategorii_OnSelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            var products = _restaurant.GetProductsByCategory(ListaKategorii.SelectedIndex);

            ListaProduktow.Items.Clear();
            foreach (var item in products)
            {
                ListaProduktow.Items.Add(item["name"]);
            }
        }

        private void ListaProduktow_OnSelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if (ListaProduktow.SelectedItem == null) return;
            Zamowienie.Items.Add(ListaProduktow.SelectedItem.ToString());
        }

        private void Submit_OnClick(object sender, RoutedEventArgs e)
        {
            _restaurant.SubmitInvoice(Zamowienie.Items);
        }

        private void Cancel_OnClick(object sender, RoutedEventArgs e)
        {
            Zamowienie.Items.Clear();
        }
    }
}
