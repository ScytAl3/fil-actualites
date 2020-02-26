#------------------------------------------------------------
#                        Script MySQL.
#------------------------------------------------------------
#-- creation de la base de donnees si elle n existe pas
CREATE DATABASE IF NOT EXISTS db_news_feed;
#-- on precise que l on va utiliser cette datbase pour creer les tables
USE db_news_feed;

#------------------------------------------------------------
# Table: USERS
#------------------------------------------------------------

CREATE TABLE users (
    userId                     int                       not null  Auto_increment,
    userLastName        varchar(75)         not null,
    userFirstName       varchar(75)         not null,    
    userPseudo              varchar(25)         not null,
    userEmail               varchar(100)       not null,
    userPassword         varchar(255)         not null,
    userSalt                    varchar(255)         not null,
    accountCreated_at               datetime     not null,
    userRole                varchar(20)             not null DEFAULT 'Default',
    CONSTRAINT users_PK PRIMARY KEY (userId),
    UNIQUE KEY unique_email (userEmail),
    UNIQUE KEY unique_pseudo (userPseudo)
) ENGINE=InnoDB;

#------------------------------------------------------------
# Table: ARTICLES
#------------------------------------------------------------

CREATE TABLE articles (
    articlesId                       int                        not null Auto_increment,
    articleUserId               int                            not null,
    articlesTitle                   varchar(255)        not null,
    articlesDescription        varchar(2550)       not null,
    articlesBody                  longtext                  not null,
    created_at                       datetime               not null,
    updated_at                       datetime                   null  ,
    CONSTRAINT articles_PK PRIMARY KEY (articlesId),
    FOREIGN KEY (articleUserId) REFERENCES users(userId)
) ENGINE=InnoDB;

#------------------------------------------------------------
# Table: PICTURES
#------------------------------------------------------------

CREATE TABLE pictures (
    picturesId                   int                    not null  Auto_increment,
    articlesId                    int                    not null,    
    pictureFilename         varchar(100)    not null,
    CONSTRAINT pictures_PK PRIMARY KEY (picturesId),
    FOREIGN KEY (articlesId) REFERENCES articles(articlesId)
) ENGINE=InnoDB;

#-----------------------------------------------------------
#                     JEU DE DONNEES
#-----------------------------------------------------------
#-----------------------------------------------------------
# Table: USERS - Data
#-----------------------------------------------------------

INSERT INTO 
    users(userLastName, userFirstName, userPseudo, userEmail, userPassword, userSalt, accountCreated_at, userRole) 
VALUES 
    ('Doe', 'John', 'whoami', 'j.doe@cci.fr', '54e4feb636204d1e5fcf49fb202946db', 'b7c8cb5b20beb2733470a65bb59722de', '2020-02-20 13:29:09', 'Admin'),              -- az3rty
    ( 'Jobs', 'Steve', 'in the sky', 'amazing@rip.com', '1f1c153c6717024f825a862901f9c3bc', '476e62fcde5fcaa1e7fc2629da120ce9', '2020-02-20 15:29:09', 'Admin'),                  -- 4pple 
    ('Tuttle', 'Archibald', 'heating engineer', 'harry.tuttle@br.com', '78169d67d449272b6bad1438b75bf4fe', '1641b4d0b9a50afbadb3cedf983c9cd1', '2020-02-21 16:25:49', DEFAULT),    -- Ninj4
    ('Bismuth', 'Paul', 'carla B', 'ns-2017@lr.fr', 'b4f56e6dca3905a5b3f4e73058ba2ab2', '5e406044172b4831cf110e63b51f0b47', '2020-02-22 19:15:25', DEFAULT),                   -- Sark0
    ('Balkany', 'Patrick', 'robin des bois', 'la-sante@gouv.fr', '4fcf95ce8284291469466c0b2aecaed8', '61a722aee2cc2e539778622bc7ee7c4d', '2020-02-22 21:09:27', DEFAULT),             -- money
    ('Abagnale', 'Frank', 'im a pilote', 'catch.me@noop.fr', 'a87f2462177f71232e05bd00f68675ef', '5d969f98d53259fe94d0245eb8d3ac26', '2020-02-22 12:04:49', DEFAULT);          -- c4tchM3

#-----------------------------------------------------------
# Table: ARTICLES - Data
#-----------------------------------------------------------
INSERT INTO
    articles(`articleUserId`,`articlesTitle`, `articlesDescription`, `articlesBody`, `created_at`)
VALUES
    (1, 'Alias aperiam est dicta non sapiente est eos.', 'Est at possimus et deserunt. Dicta sed amet ipsum dolorem quam adipisci pariatur.', 'Est similique necessitatibus molestiae accusantium necessitatibus. Occaecati sunt rem at quo officiis. Eveniet provident illo nihil earum a enim amet. Dolores excepturi impedit fugiat. Quae similique dolore deleniti rerum quos deserunt qui placeat. Architecto sit repudiandae est facere aut sunt asperiores. Blanditiis ut amet veritatis sint cupiditate dolor velit. Nihil modi voluptatem dolor tempora consequatur quod facilis. Quae perferendis illum reiciendis sequi dignissimos alias natus corporis. Illo quam enim mollitia aliquid aut.', '2020-02-18 13:29:09'),
    (2, 'Non illum est aut nemo molestiae praesentium adipisci.', 'Qui modi nam consectetur eius quia possimus omnis adipisci. Cupiditate sed sed aut.', 'Ea nesciunt sint ratione labore. Debitis totam recusandae quibusdam quisquam eius aut provident. Ea libero numquam cum in similique omnis. Aut cumque beatae ullam sapiente quia cumque. Asperiores dolore et sed magni quae eum. Tempore incidunt sed nihil voluptas. Voluptatibus consectetur eos aperiam culpa alias voluptates qui. Provident omnis ea et voluptate. Facere culpa quia eum sit omnis ab possimus repellat. Dolor error recusandae nobis dolorem ratione quibusdam. Ab quasi accusantium iure excepturi ea. Voluptatem est sunt voluptas dicta ad.', '2020-02-19 17:59:20'),
    (2, 'Sit dolores maiores voluptates sint.', 'Cum consequatur et natus hic et. Illum eos quidem omnis quaerat error quae sequi.', 'Sint et delectus aut voluptatibus eos deleniti. A iste in ex nesciunt et quasi quisquam. Aut itaque quis explicabo quos. Repellat sunt inventore omnis omnis. Doloribus enim fugiat labore sed eum sapiente. Unde mollitia ex perferendis repudiandae sed non sed velit. In totam et deserunt vel nulla numquam. Voluptatem perspiciatis provident sunt dolore. Est esse labore ut minima temporibus possimus. A numquam earum eos neque. Qui repellat deleniti repellat ducimus expedita id.', '2020-02-19 18:19:34'),
    (1, 'Maiores similique sit voluptas.', 'Neque et consequuntur optio quo aut. Adipisci ut veritatis non nostrum est nesciunt placeat.', 'Animi magnam aspernatur dolorem deleniti. Aut dolores corporis dolorum. Reprehenderit at maxime aspernatur. Minus molestias voluptatum accusantium iste consequuntur quia assumenda. Eos occaecati consequatur aspernatur sed eius. Incidunt totam expedita ipsam fugit reiciendis reiciendis quo quia. Ut harum non facere aut et quos est. Assumenda nam debitis officiis perferendis. Vitae nesciunt at laboriosam expedita deserunt.', '2020-02-20 08:29:18'),
    (2, 'Quasi ipsa est unde illo.', 'Eos veritatis adipisci omnis ipsam qui. Non officia et dolore. Vel fugit et ipsum qui autem.', 'Enim optio voluptatem animi consectetur quas. Excepturi dolores numquam modi nihil vel vero illo voluptatibus. Sint hic vitae quibusdam. Consequuntur ut laborum molestiae minus sapiente magnam aut. Molestiae placeat est et voluptatum ut. Sint et eos ullam. Molestiae eveniet debitis autem dolorem deserunt. Dolore sit dolores inventore commodi eveniet quaerat ullam esse. Quo nulla ratione et sit accusamus doloremque. Iure non qui officia quasi corporis rerum. Animi est eius inventore ut perferendis nam sapiente. Mollitia culpa distinctio dolorem tenetur qui. Illo quod dolor molestiae.', '2020-02-20 13:59:58'),
    (1, 'Possimus reprehenderit odit et error.', 'Quaerat voluptate natus ad nisi recusandae laudantium ut. Eligendi quos tempora in sunt quasi rem.', 'Dignissimos omnis voluptates mollitia autem minus qui. Animi aut commodi repellendus ea mollitia. Iure assumenda aut porro molestiae laboriosam quasi. Nihil iure libero sapiente quibusdam dolore nemo. Et dolorum sed ipsam dicta facere asperiores expedita dolorem. Facere commodi officia voluptatem pariatur mollitia voluptatem. Voluptates et non quidem.', '2020-02-24 15:19:08'),
    (1, 'Excepturi laborum ut voluptates consequuntur adipisci dolores sit iusto.', 'Culpa rerum id rem consequatur facere. Distinctio ut et quis a necessitatibus expedita itaque.', 'Minima quaerat sit facilis ipsa. Rem rem doloremque harum ratione. Modi quia nihil inventore occaecati omnis porro iure. Quia distinctio distinctio amet tempore. Quis tenetur laborum asperiores sit recusandae et. Laboriosam ab sunt quam nulla dolores eius voluptatem et. Voluptates blanditiis omnis dicta magni dolore a ullam. Quasi qui alias officia ad. Dolores quo delectus ut consectetur modi nesciunt. Dolorem autem eaque voluptatibus occaecati sapiente veritatis officiis est.', '2020-02-25 11:54:48');

#-----------------------------------------------------------
# Table: PICTURES - Data
#-----------------------------------------------------------
INSERT INTO
    pictures(`articlesId`,`pictureFilename`)
VALUES
    (1, 'celebration_01.jpg'),
    (1, 'celebration_02.jpg'),
    (2, 'empty_picture.jpg'),
    (3, 'empty_picture.jpg'),
    (4, 'concert_01.jpg'),
    (4, 'concert_02.jpg'),
    (4, 'concert_03.jpg'),
    (5, 'empty_picture.jpg'),
    (6, 'seminar_01.jpg'),
    (6, 'seminar_02.jpg'),
    (7, 'empty_picture.jpg');
