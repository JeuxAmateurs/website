<?php

namespace JA\AppBundle\Model;
use JA\AppBundle\Model\UserInterface;

/**
 * News Interface
 *
 */
interface NewsInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set "title
     *
     * @param string $title
     * @return NewsInterface
     */
    public function setTitle($title);

    /**
     * Get "title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set content
     *
     * @param string $content
     * @return NewsInterface
     */
    public function setContent($content);

    /**
     * Get content
     *
     * @return string
     */
    public function getContent();

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * Set sources
     *
     * @param string $sources
     * @return NewsInterface
     */
    public function setSources($sources);

    /**
     * Get sources
     *
     * @return string
     */
    public function getSources();

    /**
     * Set medias
     *
     * @param string $medias
     * @return NewsInterface
     */
    public function setMedias($medias);

    /**
     * Get medias
     *
     * @return string
     */
    public function getMedias();

    /**
     * Set comments
     *
     * @param string $comments
     * @return NewsInterface
     */
    public function setComments($comments);

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments();

    /**
     * Get authors
     *
     * @return UserInterface array
     */
    public function getAuthors();

    /**
     * Set authorTeam
     *
     * @param string $authorTeam
     * @return NewsInterface
     */
    public function setAuthorTeam($authorTeam);

    /**
     * Get authorTeam
     *
     * @return string
     */
    public function getAuthorTeam();

    /**
     * Set game
     *
     * @param GameInterface $game
     * @return NewsInterface
     */
    public function setGame(GameInterface $game);

    /**
     * Get game
     *
     * @return array
     */
    public function getGame();

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return NewsInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return NewsInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Add authors
     *
     * @param UserInterface $author
     * @return NewsInterface
     */
    public function addAuthor(UserInterface $author);

    /**
     * Remove authors
     *
     * @param UserInterface $author
     */
    public function removeAuthor(UserInterface $author);

    /**
     * Add mentionedGames
     *
     * @param GameInterface $mentionedGames
     * @return NewsInterface
     */
    public function addMentionedGame(GameInterface $mentionedGames);

    /**
     * Remove mentionedGames
     *
     * @param GameInterface $mentionedGames
     */
    public function removeMentionedGame(GameInterface $mentionedGames);
}
