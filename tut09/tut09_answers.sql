-- General Instructions
-- 1.	The .sql files are run automatically, so please ensure that there are no syntax errors in the file. If we are unable to run your file, you get an automatic reduction to 0 marks.
-- Comment in MYSQL 
-- Query 1: List the names of all left-handed batsmen from England. Order the results alphabetically.
SELECT player_name FROM Players
WHERE batting_hand = 'Left-handed' AND country_name = 'England'
ORDER BY player_name;

-- Query 2: List the names and age (in years, should be integer) as on 2018-12-02 (12th Feb, 2018) of all bowlers with skill “Legbreak googly” who are 28 or more in age. Order the result in decreasing order of their ages. Resolve ties alphabetically.
SELECT player_name, DATE_PART('year', AGE('2018-12-02', dob)) AS player_age
FROM Players
WHERE bowling_skill = 'Legbreak googly' AND DATE_PART('year', AGE('2018-12-02', dob)) >= 28
ORDER BY player_age DESC, player_name;

-- Query 3: List the match ids and toss winning team IDs where the toss winner of a match decided to bat first. Order result in increasing order of match ids.
SELECT match_id, toss_winner
FROM Matches
WHERE toss_decision = 'bat'
ORDER BY match_id;

-- Query 4: In the match with match id 335987, list the over ids and runs scored where at most 7 runs were scored. Order the over ids in decreasing order of runs scored. Resolve ties by listing the over ids in increasing order.
SELECT over_id, runs_scored
FROM Ball_by_Ball
WHERE match_id = 335987 AND runs_scored <= 7
ORDER BY runs_scored DESC, over_id;

-- Query 5: List the names of those batsmen who were bowled at least once in alphabetical order of their names.
SELECT DISTINCT Players.player_name
FROM Players
INNER JOIN Balls_Out ON Players.player_id = Balls_Out.player_out
ORDER BY Players.player_name;

-- Query 6: List all the match ids along with the names of teams participating (team 1, team 2), name of the winning team, and win margin where the win margin is at least 60 runs, in increasing order of win margin. Resolve ties by listing the match ids in increasing order.
SELECT Matches.match_id, Team1.name AS team_1, Team2.name AS team_2, WinningTeam.name AS winning_team, Matches.win_margin
FROM Matches
JOIN Teams AS Team1 ON Matches.team_1 = Team1.team_id
JOIN Teams AS Team2 ON Matches.team_2 = Team2.team_id
JOIN Teams AS WinningTeam ON Matches.match_winner = WinningTeam.team_id
WHERE Matches.win_margin >= 60
ORDER BY Matches.win_margin, Matches.match_id;

-- Query 7: List the names of all left-handed batsmen below 30 years of age as on 2018-12-02 (12th Feb, 2018) alphabetically.
SELECT player_name
FROM Players
WHERE batting_hand = 'Left-handed' AND DATE_PART('year', AGE('2018-12-02', dob)) < 30
ORDER BY player_name;

-- Query 8: List the match wise total for the entire series. The output should be match id, total runs. Return the results in increasing order of match ids.
SELECT match_id, SUM(runs_scored) AS total_runs
FROM Ball_by_Ball
GROUP BY match_id
ORDER BY match_id;

-- Query 9: For each match id, list the maximum runs scored in any over and the bowler bowling in that over. If there is more than one over having maximum runs, return all of them and order them in increasing order of over id. Order results in increasing order of match ids.
SELECT match_id, over_id, MAX(runs) AS maximum_runs, player_name
FROM (
    SELECT Ball_by_Ball.match_id, Ball_by_Ball.over_id, SUM(runs_scored) AS runs, Players.player_name
    FROM Ball_by_Ball
    JOIN Players ON Ball_by_Ball.bowler = Players.player_id
    GROUP BY Ball_by_Ball.match_id, Ball_by_Ball.over_id, Players.player_name
) AS subquery
GROUP BY match_id, over_id, player_name
HAVING MAX(runs) = (
    SELECT MAX(runs)
    FROM (
        SELECT Ball_by_Ball.match_id, Ball_by_Ball.over_id, SUM(runs_scored) AS runs
        FROM Ball_by_Ball
        GROUP BY Ball_by_Ball.match_id, Ball_by_Ball.over_id
    ) AS max_runs_per_over
    WHERE max_runs_per_over.match_id = subquery.match_id
    GROUP BY max_runs_per_over.match_id
)
ORDER BY match_id, over_id;

-- Query 10: List the names of batsmen and the number of times they have been “run out” in decreasing order of being “run out”. Resolve ties alphabetically.
SELECT Players.player_name, COUNT(Balls_Out.player_out) AS number
FROM Balls_Out
JOIN Players ON Balls_Out.player_out = Players.player_id
WHERE Balls_Out.kind_out = 'run out'
GROUP BY Balls_Out.player_out, Players.player_name
ORDER BY number DESC, Players.player_name;

-- Query 11: List the number of times any batsman has got out for any out type. Return results in decreasing order of the numbers. Resolve ties alphabetically (on the out type name).
SELECT Balls_Out.kind_out, COUNT(Balls_Out.player_out) AS number
FROM Balls_Out
GROUP BY Balls_Out.kind_out
ORDER BY number DESC, Balls_Out.kind_out;

-- Query 12: List the team name and the number of times any player from the team has received man of the match award. Order results alphabetically on the name of the team.
SELECT Teams.name, COUNT(Matches.man_of_the_match) AS number
FROM Matches
JOIN Teams ON Matches.man_of_the_match = Teams.team_id
GROUP BY Teams.name
ORDER BY Teams.name;

-- Query 13: Find the venue where the maximum number of wides have been given. In case of ties, return the one that comes before in alphabetical ordering. Output should contain only 1 row.
SELECT venue
FROM (
    SELECT venue, COUNT(extra_type) AS wide_count
    FROM Extra_Runs
    WHERE extra_type = 'wide'
    GROUP BY venue
    ORDER BY wide_count DESC, venue
    LIMIT 1
) AS max_wides;

-- Query 14: Find the venue(s) where the team bowling first has won the match. If there are more than 1 venues, list all of them in order of the number of wins (by the bowling team). Resolve ties alphabetically.
SELECT venue
FROM (
    SELECT venue, COUNT(CASE WHEN Matches.team_bowling = Matches.match_winner THEN 1 END) AS win_count
    FROM Matches
    GROUP BY venue
    HAVING COUNT(CASE WHEN Matches.team_bowling = Matches.match_winner THEN 1 END) > 0
) AS venues_with_wins
ORDER BY win_count DESC, venue;

-- Query 15: Find the bowler who has the best average overall. Bowling average is calculated using the following formula: bowling average = Number of runs given / Number of wickets taken (1). Calculate the average upto 3 decimal places and return the bowler with the lowest average runs per wicket. In case of tie, return the results in alphabetical order.
SELECT Players.player_name
FROM (
    SELECT bowler, SUM(runs_given) / SUM(wickets_taken) AS bowling_average
    FROM (
        SELECT bowler, COUNT(*) AS wickets_taken
        FROM Balls_Out
        WHERE kind_out != 'run out' AND kind_out != 'retired hurt'
        GROUP BY bowler
    ) AS wickets
    JOIN (
        SELECT bowler, SUM(extra_runs) + SUM(runs_scored) AS runs_given
        FROM Ball_by_Ball
        GROUP BY bowler
    ) AS runs ON wickets.bowler = runs.b
-- Query 16: List the players and the corresponding teams where the player played as “CaptainKeeper” and won the match. Order results alphabetically on the player’s name.
SELECT Players.player_name, Teams.name
FROM Matches
JOIN Players ON Matches.man_of_the_match = Players.player_id
JOIN Teams ON Matches.match_winner = Teams.team_id
WHERE Players.role = 'CaptainKeeper'
ORDER BY Players.player_name;

-- Query 17: List the names of all players and their runs scored (who have scored at least 50 runs in any match). Order result in decreasing order of runs scored. Resolve ties alphabetically.
SELECT Players.player_name, SUM(Ball_by_Ball.runs_scored) AS runs_scored
FROM Players
JOIN Ball_by_Ball ON Players.player_id = Ball_by_Ball.striker
GROUP BY Players.player_name
HAVING SUM(Ball_by_Ball.runs_scored) >= 50
ORDER BY runs_scored DESC, Players.player_name;

-- Query 18: List the player names who scored a century but their teams lost the match. Order results alphabetically.
SELECT Players.player_name
FROM (
    SELECT striker, match_id, SUM(runs_scored) AS total_runs
    FROM Ball_by_Ball
    GROUP BY striker, match_id
    HAVING SUM(runs_scored) >= 100
) AS centuries
JOIN Matches ON centuries.match_id = Matches.match_id
JOIN Players ON centuries.striker = Players.player_id
WHERE Matches.match_winner != Matches.team_batting
ORDER BY Players.player_name;

-- Query 19: List the names of all players who have taken a hat-trick (3 wickets in 3 consecutive balls) in any match. Order results alphabetically.
SELECT DISTINCT Players.player_name
FROM (
    SELECT bowler, match_id, over_id, ball_id,
           LEAD(over_id, 1) OVER (PARTITION BY bowler, match_id ORDER BY over_id, ball_id) AS next_over_id,
           LEAD(ball_id, 1) OVER (PARTITION BY bowler, match_id ORDER BY over_id, ball_id) AS next_ball_id,
           LEAD(ball_id, 2) OVER (PARTITION BY bowler, match_id ORDER BY over_id, ball_id) AS third_ball_id
    FROM (
        SELECT bowler, match_id, over_id, ball_id,
               ROW_NUMBER() OVER (PARTITION BY bowler, match_id ORDER BY over_id, ball_id) AS rn
        FROM Balls_Out
        WHERE kind_out != 'run out' AND kind_out != 'retired hurt'
    ) AS numbered
    WHERE rn = 1
) AS potential_hattricks
JOIN Balls_Out ON potential_hattricks.bowler = Balls_Out.bowler AND
                 potential_hattricks.match_id = Balls_Out.match_id AND
                 potential_hattricks.next_over_id = Balls_Out.over_id AND
                 potential_hattricks.next_ball_id = Balls_Out.ball_id AND
                 potential_hattricks.third_ball_id = Balls_Out.ball_id
JOIN Players ON potential_hattricks.bowler = Players.player_id
ORDER BY Players.player_name;

-- Query 20: List the names of all players who have taken 5 or more wickets in an innings. Order results alphabetically.
SELECT Players.player_name
FROM (
    SELECT bowler, match_id, innings_no, COUNT(*) AS wickets_taken
    FROM Balls_Out
    WHERE kind_out != 'run out' AND kind_out != 'retired hurt'
    GROUP BY bowler, match_id, innings_no
    HAVING COUNT(*) >= 5
) AS wickets
JOIN Players ON wickets.bowler = Players.player_id
ORDER BY Players.player_name;
