USE app;

CREATE TABLE IF NOT EXISTS `users` (
	`id` INT PRIMARY KEY AUTO_INCREMENT,
	`email` VARCHAR(255) NOT NULL,
	UNIQUE KEY `email_unique` (`email`) USING BTREE
);

CREATE TABLE IF NOT EXISTS `ads` (
	`id` INT PRIMARY KEY AUTO_INCREMENT,
	`url` VARCHAR(255) NOT NULL,
	`price` VARCHAR(255) NOT NULL DEFAULT 0,
	UNIQUE KEY `url_unique` (`url`) USING BTREE
);

CREATE TABLE IF NOT EXISTS `ad_on_user` (
	`user_id` INT NOT NULL,
	`ad_id` INT NOT NULL,
    PRIMARY KEY (`user_id`, `ad_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`ad_id`) REFERENCES `ads`(`id`)
)
