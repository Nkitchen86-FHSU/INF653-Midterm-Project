DROP TABLE quotes;
DROP TABLE authors;
DROP TABLE categories;

CREATE TABLE authors (
    id SERIAL PRIMARY KEY,
    author VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    category VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE quotes (
    id SERIAL PRIMARY KEY,
    author_id INT NOT NULL REFERENCES authors(id) ON DELETE RESTRICT,
    category_id INT NOT NULL REFERENCES categories(id) ON DELETE RESTRICT,
    quote TEXT NOT NULL
);

INSERT INTO authors (author)
VALUES ('Neil Armstrong'),
    ('Henry Ford'),
    ('Steve Jobs'),
    ('Arnold Schwarzenegger'),
    ('Dr. Seuss'),
    ('Warren Buffet'),
    ('Victor Hugo');

INSERT INTO categories (category)
VALUES ('Age'),
    ('Technology'),
    ('Fitness'),
    ('Literature'),
    ('Space'),
    ('Finance'),
    ('Leadership');

INSERT INTO quotes (quote, author_id, category_id)
VALUES ('That''s one small step for a man, one giant leap for mankind.', 1, 5),
    ('Today you are you! That is truer than true! There is no one alive who is you-er than you!', 5, 4),
    ('You have brains in your head. You have feet in your shoes. You can steer yourself in any direction you choose. You''re on your own, and you know what you know. And you are the guy who''ll decide where to go.', 5, 4),
    ('Anyone who stops learning is old, whether at twenty or eighty. Anyone who keeps learning stays young. The greatest thing in life is to keep your mind young.', 2, 1),
    ('Adults are obsolete children.', 5, 1),
    ('Design is the fundamental soul of a man-made creation that ends up expressing itself in successive outer layers of the product or service. The iMac is not just the color or translucence or the shape of the shell. The essence of the iMac is to be the finest possible consumer computer in which each element plays together.', 3, 2),
    ('An iPod, a phone, an internet mobile communicator... these are NOT three separate devices! And we are calling it iPhone! Today Apple is going to reinvent the phone. And here it is.', 3, 2),
    ('Technology is nothing. What''s important is that you have a faith in people, that they''re basically good and smart, and if you give them tools, they''ll do wonderful things with them.', 3, 2),
    ('I want to put a ding in the universe.', 3, 5),
    ('Bodybuilding is much like any other sport. To be successful, you must dedicate yourself 100% to your training, diet and mental approach.', 4, 3),
    ('Strength does not come from winning. Your struggles develop your strengths. When you go through hardships and decide not to surrender, that is strength.', 4, 3),
    ('A business that makes nothing but money is a poor business.', 2, 6),
    ('Exercise is bunk. If you are healthy, you don''t need it: if you are sick you should not take it.', 2, 3),
    ('It is not the employer who pays the wages. Employers only handle the money. It is the customer who pays the wages.', 2, 6),
    ('A person''s a person, no matter how small.', 5, 4),
    ('I think we''re going to the moon because it''s in the nature of the human being to face challenges. It''s by the nature of his deep inner soul... we''re required to do these things just as salmon swim upstream.', 1, 5),
    ('The important achievement of Apollo was demonstrating that humanity is not forever chained to this planet and our visions go rather further than that and our opportunities are unlimited.', 1, 5),
    ('It suddenly struck me that that tiny pea, pretty and blue, was the Earth. I put up my thumb and shut one eye, and my thumb blotted out the planet Earth. I didn''t feel like a giant. I felt very, very small.', 1, 5),
    ('Price is what you pay. Value is what you get.', 6, 6),
    ('We simply attempt to be fearful when others are greedy and to be greedy only when others are fearful.', 6, 6),
    ('In the business world, the rearview mirror is always clearer than the windshield.', 6, 6),
    ('I never attempt to make money on the stock market. I buy on the assumption that they could close the market the next day and not reopen it for five years.', 6, 6),
    ('Forty is the old age of youth; fifty the youth of old age.', 7, 1),
    ('Be a yardstick of quality. Some people aren''t used to an environment where excellence is expected.', 3, 7),
    ('If you think you can do a thing or think you can''t do a thing, you''re right.', 2, 7);