-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.1.30-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win32
-- HeidiSQL Versão:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura para tabela ftrsoftware.users_shoppings
CREATE TABLE IF NOT EXISTS `users_shoppings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shoppings_id` int(10) unsigned NOT NULL DEFAULT '0',
  `users_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shoppings_users_ref_id` (`shoppings_id`),
  KEY `users_shoppings_ref_id` (`users_id`),
  CONSTRAINT `shoppings_users_ref_id` FOREIGN KEY (`shoppings_id`) REFERENCES `shoppings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_shoppings_ref_id` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela ftrsoftware.users_shoppings: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `users_shoppings` DISABLE KEYS */;
REPLACE INTO `users_shoppings` (`id`, `shoppings_id`, `users_id`) VALUES
	(3, 80, 2),
	(4, 9, 2),
	(5, 6, 1);
/*!40000 ALTER TABLE `users_shoppings` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
