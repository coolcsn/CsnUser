--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `name`, `abbreviation`) VALUES
(1, 'English', 'en'),
(2, 'Français', 'fr'),
(3, 'Deutsch', 'de'),
(4, 'Español', 'es'),
(5, 'Italiano', 'it'),
(6, 'Български', 'bg');

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'Guest'),
(2, 'Member'),
(3, 'Admin');

--
-- Dumping data for table `roles_parents`
--

INSERT INTO `roles_parents` (`role_id`, `parent_id`) VALUES
(2, 1),
(3, 2);

--
-- Dumping data for table `questions`
--

INSERT INTO `question` (`id`, `question`) VALUES
(1, 'What was your childhood phone number?'),
(2, 'In what city did your mother born?'),
(3, 'In what city did your father born?'),
(4, 'In what city or town was your first job?');

--
-- Dumping data for table `questions`
--

INSERT INTO `state` (`id`, `state`) VALUES
(1, 'Disabled'),
(2, 'Enabled');
