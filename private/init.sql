CREATE TABLE catalogue_admin (
    account_id INT(3) AUTO_INCREMENT PRIMARY KEY,
    users VARCHAR(16) NOT NULL,
    hashed_pass VARCHAR(72) NOT NULL,
    user_name VARCHAR(50),
    profile_picture VARCHAR(100) DEFAULT '6576a8998bab18.05555084.webp	'
);

INSERT INTO catalogue_admin (users, hashed_pass)

VALUES ('instructor', '$2a$10$SRx7g.VlzG1rUHeyePmXlumohvxUSjB4hC.Ah33o0nfSQ6tWhUWSW'),
('dvo5', '$2a$10$IgzFCuvXELX./O2Eap51mezc3CTb.xXQ.oeHuXJel6DDy15Rr3uVe');

CREATE TABLE anime (
  anime_id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  synopsis TEXT NOT NULL,
  genre VARCHAR(100) NOT NULL,
  premier_date VARCHAR(20) NOT NULL,
  rating INT(11) CHECK (rating >= 1 AND rating <= 5),
  studio VARCHAR(50) NOT NULL,
  completion_status VARCHAR(20) NOT NULL,
  stream VARCHAR(100),
  artwork VARCHAR(100) DEFAULT '657758b9cca990.98543411.jpg	',
  last_edited VARCHAR(25)
);

INSERT INTO anime_catalogue (name, synopsis, genre, premier_date, rating, studio, completion_status, artwork)
VALUES 
  ('Tomodachi Game', 
   "High school student Yuuichi Katagiri holds a tight-knit group of friends, including Yutori Kokorogi, Shiho Sawaragi, Makoto Shibe, and Tenji Mikasa. However, their camaraderie is tested when the funds for a school trip are stolen, causing Shiho and Makoto to distance themselves. Deceived and rendered unconscious, the friends wake up in a white room with controversial figure Manabu-kun. He reveals that one among them orchestrated the meeting to settle a personal debt of twenty million yen. To clear the debt, they must engage in psychological games, exploring the true nature of their friendship and humanity. Isolated and distressed, the group must collaborate to navigate these challenges, but as concealed feelings and problematic pasts surface, their seemingly unbreakable bond is at risk of irreparable damage.", 
   'Suspense', 'Spring 2022', NULL, 'Okuruto Noboru', 'Watching', 'tomodachi_game_artwork.webp'),
  ('Mashle: Magic and Muscles', 
   "In a world where magical abilities are marked on individuals' faces, those without such powers face swift extermination to maintain societal magical integrity. Mash Burnedead, an anomaly with extraordinary physical strength but lacking magical abilities, lives quietly with his father in a secluded forest. When authorities discover Mash's magic deficiency, he's given an ultimatum: compete to become a 'Divine Visionary' or face perpetual persecution. To safeguard his family, Mash enrolls in the exclusive Easton Magic Academy, aiming to surpass magical elites using only his muscular prowess.", 
   'Action, Comedy, Fantasy', 'Spring 2023', 5, 'A-1 Pictures', 'Completed', 'mashle_artwork.webp'),
  ('Ascendance of a Bookworm', 
   "Urano Motosu, an ardent lover of books, meets an untimely end just as she's on the brink of achieving her dream job as a librarian. With her dying breath, she wishes for the opportunity to read more in her next life. Fate seemingly heeds her plea, and she is reborn as Myne, a fragile five-year-old in a medieval world. Myne's immediate concern is her passion for literature, but she faces frustration due to the scarcity of books in her new reality. In a society where books are rare and expensive, limited to the privileged nobles, Myne resolves to overcome the obstacle. Undeterred by the absence of readily available books, she is determined to prove the unyielding strength of her desire to read by creating them herself, even in the absence of a printing press.", 
   'Fantasy', 'Fall 2019', 4, 'Ajia-do', 'Completed', 'ascendance_of_a_bookworm_artwork.webp'),
   ('Toradora!', 
   "In the high school romantic comedy 'Toradora!', Ryuuji Takasu, a gentle student with a love for housework, bears an intimidating face that earns him the delinquent label. On the contrary, Taiga Aisaka, known as the 'Palmtop Tiger' for her feisty demeanor and wooden katana, shatters the image of a cute, fragile girl. A chance encounter reveals Taiga's sweet side, harboring a crush on Ryuuji's best friend, Yuusaku Kitamura. The situation escalates when Ryuuji confesses his feelings for Taiga's best friend, Minori Kushieda, leading to an unconventional alliance between the mismatched pair as they navigate the complexities of teenage romance.", 
   'Drama, Romance', 'Fall 2008', 5, 'J.C.Staff', 'Completed', 'toradora_artwork.webp'),
   ('That Time I Got Reincarnated as a Slime', 
   "Thirty-seven-year-old Satoru Mikami, a content but girlfriend-less corporate worker in Tokyo, meets an unexpected end when a random assailant fatally stabs him. Amidst his demise, a mysterious voice issues incomprehensible commands. Upon awakening, Satoru discovers he has reincarnated as a slime in an unfamiliar realm, gaining the ability to devour and mimic anything. Encountering the sealed Catastrophe-level monster 'Storm Dragon' Veldora, he befriends him and vows to help break the seal. In return, Veldora names him Rimuru Tempest, granting divine protection. Liberated from his former life, Rimuru sets out on a new journey, his gooey antics shaping his destiny in this transformed world.", 
   'Action, Adventure, Comedy, Fantasy', 'Fall 2018', 5, '8bit', 'Completed', 'that_time_i_got_reincarnated_as_a_slime_artwork.webp'),
   ("Frieren: Beyond Journey's End", 
   "Amidst their decade-long quest to vanquish the Demon King, the hero's party—comprising Himmel, Heiter, Eisen, and Frieren—builds enduring bonds and forges precious memories through adventures. However, for Frieren, an elven mage with a life spanning over a thousand years, the time spent with her comrades is but a fraction of her existence. As the party disperses post-victory, Frieren resumes her routine of collecting spells, seemingly unaffected by the passage of time. It is only with the passing years that she realizes the profound impact of her days with the hero's party, feeling regret for taking her companions for granted. Motivated to comprehend humans and foster genuine connections, Frieren embarks on a new journey, marking the beginning of a fresh tale.", 
   'Adventure, Drama, Fantasy', 'Fall 2023', NULL, 'Madhouse', 'Watching', 'frieren_beyond_journeys_end_artwork.webp'),
   ('The Rising of the Shield Hero', 
   "The Four Cardinal Heroes, ordinary men from modern-day Japan, are summoned to Melromarc to combat the recurring Waves of Catastrophe plaguing the kingdom. Naofumi Iwatani, cursed as the 'Shield Hero,' faces ridicule for his weak offensive capabilities and perceived lackluster personality. Betrayed and falsely accused, Naofumi endures discrimination and hatred from the kingdom. Determined to strengthen himself, he forms an unlikely alliance with Raphtalia, a demi-human slave. As the Waves threaten Melromarc, Naofumi and Raphtalia fight to protect the kingdom and its people from an ill-fated future.", 
   'Action, Adventure, Drama, Fantasy', 'Winter 2019', 5, 'Kinema Citrus', 'Completed', 'the_rising_of_the_shield_hero_artwork.webp'),
   ("Heaven's Lost Property", 
   "From childhood, Tomoki Sakurai experiences tearful dreams of angels, prompting concern from his friend Sohara Mitsuki. Seeking answers, they enlist the help of sky enthusiast Eishirou Sugata. Eishirou, convinced of a connection to the New World—a perplexing floating anomaly—forms the New World Discovery Club. As they gather to observe the sky, Tomoki's life takes an unexpected turn when a mysterious girl falls from the heavens, addressing him as her master. 'Sora no Otoshimono' unfolds the daily adventures of the club as they delve into the mysteries surrounding the Angeloids that have descended to Earth.", 
   'Comedy, Fantasy, Romance, Sci-Fi, Ecchi', 'Fall 2009', 5, 'AIC ASTA', 'Completed', 'heavens_lost_property_artwork.webp'),
   ('Reborn as a Vending Machine, I Now Wander the Dungeon', 
   "A man with a passion for vending machines awakens to find himself reborn as one of his beloved machines in a fantasy world. In need of money to function, he encounters the young girl Lammis, who, fascinated by his goods, uses her 'Blessing of Might' to bring him to the village of Clearflow Lake and names him Boxxo. Despite his inability to communicate with villagers, Boxxo becomes an integral part of the community, providing essential goods and gaining new abilities over time. His first and loyal customer, Lammis, inspires him to assist her in enhancing her skills as a young hunter.", 
   'Comedy, Fantasy', 'Summer 2023', 3, 'Studio Gokumi, AXsiZ', 'Completed', 'reborn_as_a_vending_machine_artwork.webp'),
   ("Kuroko's Basketball", 
   "Teikou Middle School's legendary basketball lineup, the 'Generation of Miracles,' dominated the scene for three years, guided by a mysterious 'Phantom Sixth Man.' The prodigies, jaded by their monstrous growth, disperse in high school. Seirin High seeks new talent and recruits Taiga Kagami, a skilled newcomer, and Tetsuya Kuroko, lacking in presence but revealed to be the Phantom Sixth Man. Kuroko, driven to prove his strength, forms a partnership with Kagami. Together with Seirin, they aim to conquer the Interhigh championship, but the return of Kuroko's former teammates complicates their journey.", 
   'Sports', 'Spring 2012', 5, 'Production I.G', 'Completed', 'kurokos_basketball_artwork.webp'),
   ('Mob Psycho 100', 
   "Eighth-grader Shigeo 'Mob' Kageyama discovers his psychic abilities but realizes their danger, choosing to suppress them. Using his powers to impress his crush becomes mundane, leading Mob to seek control under the con artist Arataka Reigen. Now exorcising spirits for pocket change, Mob's daily life is monotonous. Yet, the psychic energy he releases is a fraction of his potential. Unrestrained emotions could trigger a cataclysmic event, and attempts to prevent Mob's explosive powers seem futile.", 
   'Action, Comedy, Supernatural', 'Summer 2016', 5, 'Bones', 'Completed', 'mob_psycho_artwork.webp'),
    ('The Eminence in Shadow', 
   "From a young age, Minoru Kagenou aspires to become incredibly strong, driven not by a desire for recognition but to blend into the crowd. By day, he appears as an average student, but at night, armed with a crowbar, he ruthlessly confronts local biker gangs. A truck accident seemingly ends his ambitions, leading to a realization of human limitations. Miraculously, Minoru is reborn as Cid, the second child of the noble Kagenou family, in a magic-filled world. Now wielding the power he desired, he takes on the moniker 'Shadow' and forms Shadow Garden to combat the Cult of Diablos. However, the line between fiction and reality blurs as the cult proves to be more than Cid had imagined.", 
   'Action, Comedy, Fantasy', 'Fall 2022', 5, 'Nexus', 'Completed', 'the_eminence_in_shadow_artwork.webp'),
   ('Jujutsu Kaisen', 
   'High schooler Yuuji Itadori idly participates in baseless paranormal activities with the Occult Club, spending his days in the clubroom or visiting his bedridden grandfather in the hospital. However, his leisurely life takes a strange turn when he unknowingly encounters a cursed item, leading to a chain of supernatural events. Swallowing the item—a finger belonging to the demon Sukuna Ryoumen, the King of Curses—Yuuji is thrust into the world of Curses, discovering his own newfound powers. As he experiences the threat these Curses pose to society, he enters Tokyo Prefectural Jujutsu High School, embarking on an irreversible path as a Jujutsu sorcerer.', 
   'Action, Fantasy', 'Fall 2022', 5, 'MAPPA', 'Completed', 'jujutsu_kaisen_artwork.webp'),
   ('Another', 'In Yomiyama North Junior High''s class 3-3, transfer student Kouichi Sakakibara returns after a sick leave, drawn to the mysterious Mei Misaki. Despite classmates'' warnings and eerie rumors about a former student, Kouichi befriends Mei, uncovering a sinister truth. As tragedies unfold, Kouichi and his classmates must unravel the eerie mystery surrounding class 3-3, knowing it comes at a hefty price.', 'Horror, Mystery, Supernatural', 'Winter 2012', 4, 'P.A. Works', 'completed', 'another_artwork.webp'),
   ('Reincarnated as a Sword', "In a new world, a once-nameless sword awakens to find himself reincarnated with telekinetic powers. Meeting Fran, a young girl from the Black Cat Tribe with aspirations to evolve into a mightier beast, the sword, now named 'Shishou,' pledges to aid her on her journey. Together, they embark on the path of adventurers, forming an unbreakable partnership as Fran strives to achieve her parents' dream and overcome the challenges associated with her tribe's reputation.
   ", 'Action, Adventure, Fantasy', 'Fall 2022', 4, 'C2C', 'Completed', 'reincarnated_as_a_sword_artwork.webp'),
  ('The God of High School', 'The "God of High School" tournament has started, where Korean high school students compete for the title of the greatest fighter and the prize of a wish. Taekwondo expert Jin Mo-Ri joins and forms bonds with karate specialist Han Dae-Wi and swordswoman Yu Mi-Ra. The competition allows all martial arts and methods for victory. As they face diverse opponents, they uncover a hidden secret behind the tournament, with political candidate Park Mu-Jin closely observing every fight. Mo-Ri, Dae-Wi, and Mi-Ra are on the verge of discovering the true meaning of becoming the God of High School.', 'Action, Fantasy', 'Summer 2020', 4, 'MAPPA', 'Completed', 'the_god_of_high_school_artwork.webp');

  CREATE TABLE anime_facts (
  id INT(3) AUTO_INCREMENT PRIMARY KEY,
  facts TEXT NOT NULL,
  fact_img	VARCHAR(100) DEFAULT '657bc8f5f09bd7.42777223.webp'
)

INSERT INTO anime_facts (facts) VALUES 
  ('Studio Ghibli, founded by Hayao Miyazaki and Isao Takahata, is known for its iconic films such as Spirited Away and Princess Mononoke.'),
  ('Madhouse, a renowned anime studio, has produced acclaimed series like Death Note and One Punch Man.'),
  ('Bones, responsible for My Hero Academia and Fullmetal Alchemist: Brotherhood, is celebrated for its high-quality animation.'),
  ('The term "manga" refers to Japanese comic books and graphic novels, serving as source material for many anime series.'),
  ('The "Isekai" genre, featuring characters transported to another world, has gained popularity with series like Sword Art Online and Re:Zero.'),
  ('Shonen anime, targeted at young males, often features action-packed storylines and characters striving to become stronger.'),
  ('Josei anime, aimed at adult females, explores more mature and realistic themes in romance and relationships.'),
  ('The "Mecha" genre, featuring giant robots, is exemplified by series like Mobile Suit Gundam and Neon Genesis Evangelion.'),
  ('Cowboy Bebop is often praised for its fusion of genres, combining elements of space opera, noir, and jazz.'),
  ('Demon Slayer: Kimetsu no Yaiba became a global phenomenon, breaking box office records and winning numerous awards.'),
  ('Neon Genesis Evangelion, known for its psychological complexity, revolutionized the mecha genre and anime storytelling.'),
  ('Hayao Miyazaki, co-founder of Studio Ghibli, is considered a master of animation and has won multiple Academy Awards.'),
  ('My Hero Academia, created by Kohei Horikoshi, has become a modern classic in the superhero anime genre.'),
  ('Attack on Titan, with its intense action and intricate plot, has garnered a massive international fanbase.'),
  ('One Punch Man humorously subverts traditional superhero tropes, featuring a hero who can defeat any opponent with one punch.'),
  ('Mamoru Hosoda, known for directing films like Wolf Children and Summer Wars, is recognized for his unique storytelling.'),
  ('The term "Otaku" is used globally to describe enthusiastic anime and manga fans, but in Japan, it originally referred to someone with obsessive interests.'),
  ('Yoko Kanno, a prolific composer, created iconic soundtracks for anime series like Cowboy Bebop and Ghost in the Shell: Stand Alone Complex.'),
  ('Steins;Gate, a science fiction anime, is praised for its intricate time-travel plot and well-developed characters.'),
  ('The "Magical Girl" genre, featuring young girls with magical powers, includes classics like Sailor Moon and Cardcaptor Sakura.'),
  ('Fullmetal Alchemist: Brotherhood faithfully adapts the manga and is lauded for its compelling narrative and character development.'),
  ('Makoto Shinkai, director of Your Name (Kimi no Na wa), is known for his visually stunning and emotionally resonant films.'),
  ('Mamoru Oshii, director of Ghost in the Shell, is considered a visionary in the cyberpunk anime genre.'),
  ('Dragon Ball, created by Akira Toriyama, is a cornerstone of the shonen genre and has had a lasting impact on anime and pop culture.'),
  ('The term "Moe" refers to a specific style and character archetype that often elicits a feeling of affection or adoration.'),
  ('The Fate series, featuring various adaptations and spin-offs, explores the concept of the Holy Grail War with historical and mythical characters.'),
  ('The "Slice of Life" genre portrays everyday life and ordinary experiences, with series like Clannad and March Comes in Like a Lion.'),
  ('The influential manga magazine "Shonen Jump" has introduced many iconic series, including Naruto, One Piece, and Dragon Ball.'),
  ('J-Horror, short for Japanese Horror, has influenced anime series like Another and Tokyo Ghoul, known for their eerie and suspenseful themes.'),
  ('The "Iyashikei" genre, known as "healing" anime, aims to provide a soothing and calming experience, as seen in series like Aria and Natsume\'s Book of Friends.'),
  ('The "Harem" genre involves a protagonist surrounded by a group of characters romantically interested in them, as seen in series like The World God Only Knows.'),
  ('The term "Senpai" and "Kohai" are commonly used in anime to denote senior and junior relationships, often in school or workplace settings.'),
  ('Astro Boy (Tetsuwan Atom), created by Osamu Tezuka, is considered a pioneering work and a classic in the history of anime.'),
  ('Hunter x Hunter, created by Yoshihiro Togashi, is known for its complex characters, intricate world-building, and strategic battles.'),
  ('The "Isekai" subgenre has seen a surge in popularity, with series like Re:Zero and That Time I Got Reincarnated as a Slime gaining widespread acclaim.'),
  ('Yuri!!! on ICE received praise for its realistic portrayal of figure skating and positive representation of LGBTQ+ relationships.'),
  ('Miyazaki\'s Spirited Away is the first and only non-English language film to win the Academy Award for Best Animated Feature.'),
  ('The term "Chibi" refers to a style of character design featuring small and cute versions of characters, often used for comedic effect.'),
  ('K-On!, a slice-of-life anime about a high school music club, is known for its lighthearted humor and emphasis on friendship.'),
  ('The "Isekai" genre often features protagonists with overpowered abilities, as seen in series like Overlord and The Rising of the Shield Hero.'),
  ('The term "Fan Service" refers to gratuitous elements included in anime, such as suggestive scenes or character designs, intended to please fans.'),
  ('The "Shojo" genre, aimed at young females, often explores themes of romance, relationships, and personal growth.'),
  ('The Monogatari series, known for its intricate dialogue and unique visual style, has gained a dedicated fanbase.'),
  ('The "Shonen Ai" and "Yaoi" genres focus on romantic and/or sexual relationships between male characters, as seen in series like Loveless and Gravitation.'),
  ('The anime industry has a strong influence on fashion, with characters often setting trends and inspiring clothing styles.'),
  ('The "Mecha" genre has evolved over the years, with classics like Mobile Suit Gundam inspiring newer series like Code Geass and Aldnoah.Zero.'),
  ('The term "Light Novel" refers to a style of written fiction in Japan, often accompanied by manga-style illustrations, and many anime series are adaptations of light novels.'),
  ('Anime, derived from the English word "animation," represents a distinctive style of Japanese animated art characterized by vibrant visuals, dynamic characters, and imaginative themes.'),
  ('Contrary to being a genre, anime functions as a versatile medium encompassing a multitude of genres, including action, romance, comedy, horror, sci-fi, fantasy, and more.'),
  ('A global entertainment phenomenon, anime commands 60% of the worldwide animation market. Its diverse appeal transcends cultural boundaries, captivating audiences around the globe.'),
  ('The inception of anime dates back to 1917 with "The Story of the Concierge Mukuzo Imokawa," the pioneering work of Oten Shimokawa. This marked the beginning of an artistic journey that has evolved significantly over the years.'),
  ('Boasting over 7,000 episodes, "Sazae-san" holds the record as the longest-running anime series since its debut in 1969. This slice-of-life series offers a glimpse into the everyday life of a suburban Japanese family.'),
  ('"Spirited Away," released in 2001 by Studio Ghibli and directed by Hayao Miyazaki, stands as the highest-grossing anime film of all time. Its captivating narrative earned it the distinction of being the first and only anime to win an Academy Award for Best Animated Feature.'),
  ('The Pokémon franchise, originating as a video game series in 1996, has grown into the most successful anime franchise, generating over $90 billion in total revenue. This multifaceted phenomenon includes an anime series, movies, trading cards, toys, and more.'),
  ('With over 900 episodes and counting since 1999, "One Piece" claims the title of the most-watched anime series. Eiichiro Oda\'s manga serves as the foundation for this enduring tale following Monkey D. Luffy\'s quest to become the king of the pirates.'),
  ('Considered the most influential anime series, "Neon Genesis Evangelion" (1995) delves into psychological and philosophical themes, exploring identity, depression, religion, and human nature.'),
  ('On the controversial front, "School Days" (2007) depicts the repercussions of infidelity, betrayal, and violence among high school students. Its provocative content sparked debates and discussions within the anime community.'),
  ('Topping the charts in terms of production costs, "The Legend of the Galactic Heroes: Die Neue These" (2018) presents a space opera chronicling the epic conflict between the Galactic Empire and the Free Planets Alliance.'),
  ('For critical acclaim, "Fullmetal Alchemist: Brotherhood" (2009) emerges as a dark fantasy masterpiece. Adapted from Hiromu Arakawa\'s manga, it follows the journey of two brothers seeking to restore their bodies after a failed alchemical experiment.'),
  ('As for genres, shonen stands out as the most popular among young male audiences, featuring action, adventure, comedy, and friendship. Notable examples include "Dragon Ball," "Naruto," "Bleach," "One Piece," and "My Hero Academia."'),
  ('Shojo captures the hearts of young female audiences with its focus on romance, drama, comedy, and slice of life. Beloved titles include "Sailor Moon," "Cardcaptor Sakura," "Fruits Basket," "Ouran High School Host Club," and "Kimi ni Todoke."'),
  ('Appealing to mature male audiences, seinen explores themes of violence, sexuality, horror, mystery, and psychology. Exemplary series encompass "Ghost in the Shell," "Akira," "Berserk," "Monster," and "Death Note."'),
  ('Josei, appealing to adult females, features sophisticated stories on career and relationships, with notable titles like "Nana" and "Paradise Kiss."'),
  ('Isekai, transporting protagonists to alternate worlds, has gained international popularity with series like "Sword Art Online" and "Re:Zero."'),
  ('Mecha, spotlighting giant robots, encompasses classics such as "Gundam" and "Evangelion," captivating audiences with thrilling technological narratives.'),
  ('Gag, specializing in absurd humor, delivers laughs through series like "Gintama" and "One Punch Man."'),
  ('Psychological horror, offering intense and unsettling experiences, includes acclaimed titles like "Hellsing" and "Tokyo Ghoul."'),
  ('Detective, engaging audiences with mysteries and crimes, features iconic series like "Detective Conan" and "Death Note."'),
  ('Magic, exploring supernatural powers, enchants viewers with titles like "Fullmetal Alchemist" and "Fairy Tail."'),
  ('Cyberpunk, set in futuristic dystopias, delivers thought-provoking narratives in anime like "Akira" and "Ghost in the Shell."'),
  ('Sports, celebrating physical prowess, inspires with series like "Haikyu!!" and "Yuri!!! on Ice."'),
  ('Music, highlighting artistic performances, captivates audiences in anime such as "Your Lie in April" and "Nana."'),
  ('Slice of life, portraying everyday situations, resonates with audiences through series like "Clannad" and "Lucky Star."'),
  ('Romance, delving into emotional relationships, touches hearts with anime like "Toradora" and "Your Name."'),
  ('Ecchi, incorporating sexual content, adds humor to series like "High School DxD" and "Food Wars."'),
  ('Harem, featuring multiple romantic interests, spices up narratives in anime like "The World God Only Knows" and "Date A Live."');

