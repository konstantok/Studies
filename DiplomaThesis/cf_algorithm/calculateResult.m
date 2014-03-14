% 
% =================================
% 
% Univesrity of Patras 
% Computer Engineering and Informatics Department 
% 
% Diploma Thesis 
% Aimed Product Suggestion to Social Network Users 
% 
% Konstantinos Konstantopoulos kkonstanto@ceid.upatras.gr
% 
% =================================
%
% Prepare Dataset from Raw Data
prepareDataset;

% Clear Variables 
clear I count i temp;

% Create Train/Test Sets 
createTestSets;

% Load Correlation Matrices
load CorrelationMatrices.mat


% Define Constants 
a = 0.85; 
errorTolerance = 10^-6; 
maxIterations = 200; 



COS1_RES = getResult(Pref1, COS1, a, errorTolerance, maxIterations);
COS2_RES = getResult(Pref2, COS2, a, errorTolerance, maxIterations);
COS3_RES = getResult(Pref3, COS3, a, errorTolerance, maxIterations);
COS4_RES = getResult(Pref4, COS4, a, errorTolerance, maxIterations);
COS5_RES = getResult(Pref5, COS5, a, errorTolerance, maxIterations);

% Evaluating Method
cos_recall = zeros(1, 5);
cos_precision = zeros(1, 5);
[cos_recall(1), cos_precision(1)] = calculateMetrics( TestSet1, TrainSet1, COS1_RES, 943, 4);
[cos_recall(2), cos_precision(2)] = calculateMetrics( TestSet2, TrainSet2, COS2_RES, 943, 4);
[cos_recall(3), cos_precision(3)] = calculateMetrics( TestSet3, TrainSet3, COS3_RES, 943, 4);
[cos_recall(4), cos_precision(4)] = calculateMetrics( TestSet4, TrainSet4, COS4_RES, 943, 4);
[cos_recall(5), cos_precision(5)] = calculateMetrics( TestSet5, TrainSet5, COS5_RES, 943, 4);

cosine_recall = mean(cos_recall)
cosine_precision = mean(cos_precision)

%clear COS1 COS2 COS3 COS4 COS5 COS1_RES COS2_RES COS3_RES COS4_RES COS5_RES 


% Same procedure as before
ACOS1_RES = getResult(Pref1, ACOS1, a, errorTolerance, maxIterations);
ACOS2_RES = getResult(Pref2, ACOS2, a, errorTolerance, maxIterations);
ACOS3_RES = getResult(Pref3, ACOS3, a, errorTolerance, maxIterations);
ACOS4_RES = getResult(Pref4, ACOS4, a, errorTolerance, maxIterations);
ACOS5_RES = getResult(Pref5, ACOS5, a, errorTolerance, maxIterations);

adjcos_recall = zeros(1, 5);
adjcos_precision = zeros(1, 5);
[adjcos_recall(1), adjcos_precision(1)] = calculateMetrics( TestSet1, TrainSet1, ACOS1_RES, 943, 4);
[adjcos_recall(2), adjcos_precision(2)] = calculateMetrics( TestSet2, TrainSet2, ACOS2_RES, 943, 4);
[adjcos_recall(3), adjcos_precision(3)] = calculateMetrics( TestSet3, TrainSet3, ACOS3_RES, 943, 4);
[adjcos_recall(4), adjcos_precision(4)] = calculateMetrics( TestSet4, TrainSet4, ACOS4_RES, 943, 4);
[adjcos_recall(5), adjcos_precision(5)] = calculateMetrics( TestSet5, TrainSet5, ACOS5_RES, 943, 4);

adj_cosine_recall = mean(adjcos_recall)
adj_cosine_precision = mean(adjcos_precision)

%clear ACOS1 ACOS2 ACOS3 ACOS4 ACOS5 ACOS1_RES ACOS2_RES ACOS3_RES ACOS4_RES ACOS5_RES


PEAR1_RES = getResult(Pref1, PC1, a, errorTolerance, maxIterations);
PEAR2_RES = getResult(Pref2, PC2, a, errorTolerance, maxIterations);
PEAR3_RES = getResult(Pref3, PC3, a, errorTolerance, maxIterations);
PEAR4_RES = getResult(Pref4, PC4, a, errorTolerance, maxIterations);
PEAR5_RES = getResult(Pref5, PC5, a, errorTolerance, maxIterations);

pear_recall = zeros(1, 5);
pear_precision = zeros(1, 5);
[pear_recall(1), pear_precision(1)] = calculateMetrics( TestSet1, TrainSet1, PEAR1_RES, 943, 4);
[pear_recall(2), pear_precision(2)] = calculateMetrics( TestSet2, TrainSet2, PEAR2_RES, 943, 4);
[pear_recall(3), pear_precision(3)] = calculateMetrics( TestSet3, TrainSet3, PEAR3_RES, 943, 4);
[pear_recall(4), pear_precision(4)] = calculateMetrics( TestSet4, TrainSet4, PEAR4_RES, 943, 4);
[pear_recall(5), pear_precision(5)] = calculateMetrics( TestSet5, TrainSet5, PEAR5_RES, 943, 4);

pearson_recall = mean(pear_recall)
pearson_precision = mean(pear_precision)

%clear PC1 PC2 PC3 PC4 PC5 PEAR1_RES PEAR2_RES PEAR3_RES PEAR4_RES PEAR5_RES

