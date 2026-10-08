-- Backup of 75 rows from `periodic_test_syllabus` deleted 2026-10-08T14:43:08+00:00
CREATE TABLE IF NOT EXISTS `periodic_test_syllabus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classesID` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `syllabus` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('1','1','ENGLISH','Lesson No.1,\nPoem -All of me\nGrammar-Lesson No.1\nComposition - My Self');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('2','2','ENGLISH','Lesson No.1\nPoem- How  they sleep \nGrammar-Lesson No.1 & 2\nComposition - My Pet Cat');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('3','3','ENGLISH','Lesson No.1 & 2  \nPoem - I meant to do my work today. \nGrammar - Lesson No.1,2, 3, 4');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('4','4','ENGLISH','Lesson No.1 & 2\nPoem -Topsy Turvey L & \nGrammer - Lesson No.1,2 &3');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('5','5','ENGLISH','Lesson No.1 & 2 Poem\nGrammar- Lesson based');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('6','6','ENGLISH','Lesson No. 1 \nPoem NO. 3\nGrammar - Refer the notebook & lesson based');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('7','7','ENGLISH','Lesson No. 1 \nPoem NO. 3\nGrammar - Refer the notebook & lesson based');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('8','8','ENGLISH','1. Children of India\n2. Nature - The Gentlest Mother\n3. Ada\'s Violin.\n4. One man, one movement.');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('9','1','HINDI','अ, आ , इ, ई, की मात्राओं के शब्द, वाक्य, और इन्हीं पाठों पर आधारित व्याकरण।');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('10','2','HINDI','पाठ - १, २ और इन पाठों पर आधारित व्याकरण।');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('11','3','HINDI','पाठ - १, २ और इन पाठों पर आधारित व्याकरण');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('12','4','HINDI','पाठ - १,२ और इन पाठों पर आधारित व्याकरण , अनुच्छेद लेखन।');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('13','5','HINDI','पाठ - १,२ और पाठों पर आधारित व्याकरण , अनुच्छेद लेखन।');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('14','6','HINDI','पाठ - १,२ और पाठों पर आधारित व्याकरण , अनुच्छेद लेखन।');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('15','7','HINDI','पाठ-1 माँ,कह एक कहानी(कविता)\nपाठ-2 तीन बुद्धिमान\nऔर इन पाठों पर आधारित व्याकरण');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('16','8','HINDI','पाठ-1 स्वदेश(कविता)\nपाठ-2 दो गौरैया \nऔर इन पाठों पर आधारित व्याकरण');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('17','1','MARATHI','* स्वर (अ ते अ-)\n* Page no 5 to 24');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('18','2','MARATHI','पाठ १,३,४');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('19','3','MARATHI','१) संत सावता माळी \n२) पाऊस (कविता) \n३) उडते कासव \nपाठावर आधारित व्याकरण');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('20','4','MARATHI','पाठ - १ ते ४\nपाठावर आधारित व्याकरण');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('21','5','MARATHI','१) संत ज्ञानेश्वर \n२) देणाऱ्याने देत जावे (कविता) \n३) बादशहाची किंमत \nव्याकरण - पाठावर आधारित व्याकरण');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('22','6','MARATHI','श्रुतलेखन ,अपठित गद्य \nव्याकरण - नाम, सर्वनाम, विशेषण, क्रियापद, काळ, समानार्थी शब्द, विरुद्धार्थी शब्द, लिंग ,वचन.');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('23','7','MARATHI','२) श्यामचे बंधुप्रेम \n३) माझ्या अंगणात (कविता) \nव्याकरण \nसमानार्थी शब्द, विरुद्धार्थी शब्द, लिंग, वचन, शब्दांच्या जाती (क्रियाविशेषण अव्ययाचे प्रकार ) वाक्यांचे प्रकार');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('24','8','MARATHI','२) मी चित्रकार कसा झालो! \n३) प्रभात (कविता) \nव्याकरण \nसमानार्थी शब्द, विरुद्धार्थी शब्द, लिंग, वचन, शब्दांच्या जाती, वाक्यांचे प्रकार');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('25','4','SANSKRIT','पाठ-  १,२');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('26','5','SANSKRIT','पाठ- १\nपाठ- २');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('27','6','SANSKRIT','संस्कृत भाषा परिचय-\nमम परिचय-\nपाठ- १,२');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('28','7','SANSKRIT','पाठ- १,२\nपाठाधारित व्याकरण');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('29','8','SANSKRIT','पाठ- १,२\nपाठाधारित व्याकरणम्');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('30','1','MATH','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('31','2','MATH','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('32','3','MATH','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('33','4','MATH','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('34','5','MATH','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('35','6','MATH','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('36','7','MATH','Lesson No.1 & 2\n(till exercise no. 2.3)');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('37','8','MATH','Lesson No.1. A square');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('38','1','EVS / SCI','Lesson No.1, 2 & 3');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('39','2','EVS / SCI','Lesson No.1, 2 & 3');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('40','3','EVS / SCI','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('41','4','EVS / SCI','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('42','5','EVS / SCI','Lesson No.1 & 3');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('43','6','EVS / SCI','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('44','7','EVS / SCI','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('45','8','EVS / SCI','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('46','3','SOCIAL','Lesson No.1, 2 & 3');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('47','4','SOCIAL','Lesson No.1, 2 & 3');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('48','5','SOCIAL','Lesson No.1, 2 & 3');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('49','6','SOCIAL','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('50','7','SOCIAL','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('51','8','SOCIAL','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('52','1','ART','1) House\n1) Umbrella');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('53','2','ART','1) Parrot \n2) Rainbow');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('54','3','ART','1) Warli art \n2) Underwater Scenery');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('55','4','ART','1) 2D Design\n ( geometry shapes )\n2) 2D Design\n (different flowers,leaf, random)');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('56','5','ART','1) Pot disign \n2) Parrots');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('57','6','ART','1) Flowers Pot\n2) Lotus design');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('58','7','ART','1) Mirror design drawing \n2) Object, stippling drawing');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('59','8','ART','1) Any Memory drawing\n2) My School (Memory)');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('60','1','COMPUTER','Lesson No.1');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('61','2','COMPUTER','Lesson No.1');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('62','3','COMPUTER','Lesson No.1');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('63','4','COMPUTER','Lesson No.1');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('64','5','COMPUTER','Lesson No.1');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('65','6','COMPUTER','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('66','7','COMPUTER','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('67','8','COMPUTER','Lesson No.1 & 2');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('68','1','G.K','Page no.1 to 11');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('69','2','G.K','Page no.1 to 11');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('70','3','G.K','Page no.1 to 12');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('71','4','G.K','Pg no 1 to 6');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('72','5','G.K','1 to 13');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('73','6','G.K','Current Affairs');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('74','7','G.K','Current affairs');
INSERT INTO `periodic_test_syllabus` (`id`,`classesID`,`subject`,`syllabus`) VALUES ('75','8','G.K','Current affairs');
