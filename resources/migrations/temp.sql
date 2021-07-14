CREATE SCHEMA IF NOT EXISTS public;

CREATE  TABLE public.chapter ( 
	id                   uuid  NOT NULL ,
	title                varchar(60)  NOT NULL ,
	code             varchar  NOT NULL ,
	picture              varchar(100)   ,
	pages                varchar   ,
	description          text   ,
	publish_at           timestamp   ,
	created_at           timestamp DEFAULT current_timestamp  ,
	CONSTRAINT pk_issues_id_1 PRIMARY KEY ( id )
 );

CREATE  TABLE public.person ( 
	id                   uuid  NOT NULL ,
	fullname             varchar(80)  NOT NULL ,
	picture              varchar(100)   ,
	created_at           timestamp DEFAULT current_timestamp  ,
	CONSTRAINT pk_pepole_id PRIMARY KEY ( id )
 );

CREATE  TABLE public.publisher ( 
	id                   uuid  NOT NULL ,
	name                 varchar(40)  NOT NULL ,
	logo                 varchar(100)   ,
	website              varchar(50)   ,
	created_at           timestamp DEFAULT current_timestamp  ,
	CONSTRAINT pk_publishers_id PRIMARY KEY ( id )
 );

CREATE  TABLE public.serie ( 
	id                   uuid  NOT NULL ,
	publiser_id          uuid  NOT NULL ,
	title                varchar(40)  NOT NULL ,
	"year"               varchar(4)   ,
	picture              varchar(100)   ,
	ended                boolean DEFAULT false  ,
	followers            integer DEFAULT 0 NOT NULL ,
	status               smallint DEFAULT 0 NOT NULL ,
	created_at           timestamp DEFAULT current_timestamp  ,
	CONSTRAINT pk_serie_id PRIMARY KEY ( id )
 );

CREATE  TABLE public."user" ( 
	id                   uuid  NOT NULL ,
	CONSTRAINT pk_tbl_id PRIMARY KEY ( id )
 );

CREATE  TABLE public.user_serie ( 
	id                   uuid  NOT NULL ,
	user_id              uuid  NOT NULL ,
	serie_id             uuid  NOT NULL ,
	gender               varchar(10) DEFAULT 'all' NOT NULL ,
	created_at           timestamp DEFAULT current_timestamp  ,
	CONSTRAINT pk_user_follow_serie PRIMARY KEY ( id ),
	CONSTRAINT unq_user_follow_serie UNIQUE ( user_id, serie_id ) 
 );

COMMENT ON COLUMN public.user_serie."gender" IS 'all, chapters, volumes';

CREATE  TABLE public.volume ( 
	id                   uuid  NOT NULL ,
	title                varchar(60)  NOT NULL ,
	code             varchar  NOT NULL ,
	picture              varchar(100)   ,
	pages                varchar   ,
	description          text   ,
	publish_at           timestamp   ,
	created_at           timestamp DEFAULT current_timestamp  ,
	CONSTRAINT pk_issues_id_0 PRIMARY KEY ( id )
 );

CREATE  TABLE public.volume_chapter ( 
	id                   uuid  NOT NULL ,
	chapter_id           uuid  NOT NULL ,
	volume_id            uuid  NOT NULL ,
	created_at           timestamp DEFAULT current_timestamp  ,
	CONSTRAINT pk_volumes_contain_issues PRIMARY KEY ( id ),
	CONSTRAINT unq_volume_chapter_volume_id UNIQUE ( volume_id, chapter_id ) 
 );

CREATE  TABLE public.comic ( 
	id                   uuid  NOT NULL ,
	serie_id             uuid  NOT NULL ,
	volume_id            uuid   ,
	chapter_id           uuid   ,
	readers              integer DEFAULT 0 NOT NULL ,
	status               smallint DEFAULT 0 NOT NULL ,
	created_at           timestamp DEFAULT current_timestamp  ,
	CONSTRAINT pk_issues_id PRIMARY KEY ( id )
 );

CREATE  TABLE public.person_issue ( 
	id                   uuid  NOT NULL ,
	issue_id             uuid  NOT NULL ,
	person_id            uuid  NOT NULL ,
	job                  varchar(40)   ,
	created_at           timestamp DEFAULT current_timestamp  ,
	CONSTRAINT pk_person_issue PRIMARY KEY ( id ),
	CONSTRAINT unq_person_issue UNIQUE ( issue_id, person_id ) 
 );

CREATE  TABLE public.review ( 
	id                   uuid  NOT NULL ,
	user_id              uuid  NOT NULL ,
	comic_id             uuid  NOT NULL ,
	stars                smallint   ,
	status               bigint   ,
	created_at           timestamp DEFAULT CURRENT_DATE  ,
	updated_at           timestamp DEFAULT current_timestamp NOT NULL ,
	CONSTRAINT pk_reviews_id PRIMARY KEY ( id )
 );

CREATE  TABLE public.user_comic ( 
	id                   uuid  NOT NULL ,
	comic_id             uuid  NOT NULL ,
	user_id              uuid  NOT NULL ,
	page                 integer   ,
	ended                boolean DEFAULT false NOT NULL ,
	created_at           timestamp DEFAULT current_timestamp NOT NULL ,
	updated_at           timestamp DEFAULT current_timestamp NOT NULL ,
	CONSTRAINT pk_user_read_issue_id PRIMARY KEY ( id )
 );

CREATE  TABLE public."comment" ( 
	id                   uuid  NOT NULL ,
	user_id              uuid  NOT NULL ,
	review_id            uuid  NOT NULL ,
	content           text  NOT NULL ,
	status               smallint DEFAULT 0 NOT NULL ,
	created_at           timestamp DEFAULT CURRENT_DATE  ,
	CONSTRAINT pk_reviews_id_0 PRIMARY KEY ( id )
 );

ALTER TABLE public.comic ADD CONSTRAINT fk_issues_serie FOREIGN KEY ( serie_id ) REFERENCES public.serie( id );

ALTER TABLE public.comic ADD CONSTRAINT fk_issue_chapter FOREIGN KEY ( chapter_id ) REFERENCES public.chapter( id );

ALTER TABLE public.comic ADD CONSTRAINT fk_issue_volume FOREIGN KEY ( volume_id ) REFERENCES public.volume( id );

ALTER TABLE public."comment" ADD CONSTRAINT fk_reviews_user_0 FOREIGN KEY ( user_id ) REFERENCES public."user"( id );

ALTER TABLE public."comment" ADD CONSTRAINT fk_comments_reviews FOREIGN KEY ( review_id ) REFERENCES public.review( id );

ALTER TABLE public.person_issue ADD CONSTRAINT fk_person_write_issue_issues_1 FOREIGN KEY ( issue_id ) REFERENCES public.comic( id );

ALTER TABLE public.person_issue ADD CONSTRAINT fk2_person_write_issue_pepole_1 FOREIGN KEY ( person_id ) REFERENCES public.person( id );

ALTER TABLE public.review ADD CONSTRAINT fk_reviews_issues FOREIGN KEY ( comic_id ) REFERENCES public.comic( id );

ALTER TABLE public.review ADD CONSTRAINT fk_reviews_user FOREIGN KEY ( user_id ) REFERENCES public."user"( id );

ALTER TABLE public.serie ADD CONSTRAINT fk_serie_publishers FOREIGN KEY ( publiser_id ) REFERENCES public.publisher( id );

ALTER TABLE public.user_comic ADD CONSTRAINT fk_user_read_issue_issues FOREIGN KEY ( comic_id ) REFERENCES public.comic( id );

ALTER TABLE public.user_comic ADD CONSTRAINT fk2_user_read_issue FOREIGN KEY ( user_id ) REFERENCES public."user"( id );

ALTER TABLE public.user_serie ADD CONSTRAINT fk_user_follow_serie_serie FOREIGN KEY ( serie_id ) REFERENCES public.serie( id );

ALTER TABLE public.user_serie ADD CONSTRAINT fk2_user_follow_serie_user FOREIGN KEY ( user_id ) REFERENCES public."user"( id );

ALTER TABLE public.volume_chapter ADD CONSTRAINT fk_volume_chapter_volume FOREIGN KEY ( volume_id ) REFERENCES public.volume( id );

ALTER TABLE public.volume_chapter ADD CONSTRAINT fk_volume_chapter_chapter FOREIGN KEY ( chapter_id ) REFERENCES public.chapter( id );