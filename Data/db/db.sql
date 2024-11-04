CREATE TABLE `tennis_players` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `level` int NOT NULL,
  `strength` int NOT NULL,
  `speed` int NOT NULL,
  `reaction_time` decimal(4,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=376 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `tennis_tournaments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `disputed` tinyint NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

CREATE TABLE `tennis_players_tennis_tournaments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tennis_tournament_id` int NOT NULL,
  `tennis_player_id` int NOT NULL,
  `win` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tournament_idx` (`tennis_tournament_id`),
  KEY `player_idx` (`tennis_player_id`),
  CONSTRAINT `player` FOREIGN KEY (`tennis_player_id`) REFERENCES `tennis_players` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tournament` FOREIGN KEY (`tennis_tournament_id`) REFERENCES `tennis_tournaments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=273 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
