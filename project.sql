
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `text` varchar(1000) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `post_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



INSERT INTO `comments` (`comment_id`, `text`, `created_at`, `user_id`, `post_id`) VALUES
(78, 'hhhhhhhhhhhhhhhhhhh', '2022-05-31 01:08:48', '1', 14),
(79, 'hhhhhhhhhhhhhhhhh', '2022-05-31 01:09:03', '1', 15),
(81, 'hhhhhhhhhhhhhhhhh', '2022-05-31 01:10:44', '1', 16),
(82, 'HTML best ', '2022-05-31 01:10:58', '1', 16),
(83, '.', '2022-05-31 01:14:57', '1', 15);



CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `picture` text NOT NULL,
  `create_at` varchar(255) NOT NULL,
  `user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `posts` (`post_id`, `text`, `picture`, `create_at`, `user_id`) VALUES
(14, 'learn CSS 3 in 2022 from zero to hero ', '62912a77cd85c1.60662930-css3.jpg', '2022-05-27 21:45:59', 19),
(15, 'MySQL from scratch from zero to master', '62912a9101c023.02276411-mysql.png', '2022-05-27 21:46:25', 19),
(16, 'learn HTML in 2022', '62953221ec8a96.31676899-html.png', '2022-05-30 23:07:45', 1);


CREATE TABLE `users` (
  
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `picture` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



INSERT INTO `users` (`id`, `username`, `email`, `password`, `picture`) VALUES
(1, 'riadh coding', 'riadh@gmail.com', '123456789', '6286e5bf4d3ce3.62751930-python.png'),
(19, 'david hat', 'david@gmail.com', 'david@gmail.com', '628eae39d57a41.33351231-php.png');


ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);


ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);


ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;


ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;


ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;



