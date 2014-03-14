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
% Find Train - Test Sets dimensions
M = max(R(:,1));
N = max(R(:,2));

% Create Train - Test Sets
% Create Test Sets
TestSet1 = sparse(N,M);
TestSet2 = sparse(N,M);
TestSet3 = sparse(N,M);
TestSet4 = sparse(N,M);
TestSet5 = sparse(N,M);

[rows, cols, vals] = find(X);

for i=1:size(rows)
    
    chooseTestSet = rand;
        
    if chooseTestSet < 0.2 
        TestSet1(rows(i), cols(i)) = vals(i);
        continue
    end

    if chooseTestSet < 0.4 
        TestSet2(rows(i), cols(i)) = vals(i);
        continue
    end

    if chooseTestSet < 0.6 
        TestSet3(rows(i), cols(i)) = vals(i);
        continue
    end

    if chooseTestSet < 0.8 
        TestSet4(rows(i), cols(i)) = vals(i);
        continue
    end

    TestSet5(rows(i), cols(i)) = vals(i);

end

clear i;

% Create Train Sets
TrainSet1 = X - TestSet1;
TrainSet2 = X - TestSet2;
TrainSet3 = X - TestSet3;
TrainSet4 = X - TestSet4;
TrainSet5 = X - TestSet5;

TrainSet1 = sparse(TrainSet1);
TrainSet2 = sparse(TrainSet2);
TrainSet3 = sparse(TrainSet3);
TrainSet4 = sparse(TrainSet4);
TrainSet5 = sparse(TrainSet5);


% Create Preference Vectors - Normalized Train Sets

Pref1 = CreatePref(TrainSet1);
Pref2 = CreatePref(TrainSet2);
Pref3 = CreatePref(TrainSet3);
Pref4 = CreatePref(TrainSet4);
Pref5 = CreatePref(TrainSet5);

% Verification 
% nnz(X)
% nnz(TestSet1) + nnz(TrainSet1)
% nnz(TestSet2) + nnz(TrainSet2)
% nnz(TestSet3) + nnz(TrainSet3)
% nnz(TestSet4) + nnz(TrainSet4)
% nnz(TestSet5) + nnz(TrainSet5)

