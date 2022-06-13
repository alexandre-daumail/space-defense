# Answers

Here are my answers to the questions asked at the end of the test document :

## Q&A

#### 1. What is the complexity of your algorithm (in Big O notation)?

I would say that my algorithm (pairing function) is rated O(n²) in Big O notation because it has two nested loops. It's a bad algorithm because if n is a big number the process will take too much memory for the computer to calculate (and too much time).

#### 2. How would you improve your algorithm?

I would have created an array to store the "need to be paired" vessels and updated it depending on different situations.

#### 3. How would your adapt your algorithm to three dimensions? How would that affect the complexity? 

3D adds an extra "z" for the coordinates. The distance would be the square root of ((x1 - x2)² + (y1 - y2)² + (z1 - z2)²) and the complexity would be O(n^3)
